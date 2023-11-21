<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Support\Collection;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Http\Request;
use App\Models\Block_user;
use App\Mail\NewMessageMail;

class MessageController extends Controller
{
    /**
     * Handle the chat page of the current user
     *
     * @return string
     */
    public function index(): string
    {
        $currentUserId = auth()->id();
        $users         = $this->getUsersByConversations($currentUserId);

        return view(theme('new.message'), ['users' => $users, 'userId' => $currentUserId]);
    }

    /**
     * Handle the chat page of specific user
     *
     * @param int $userId
     *
     * @return string
     */
    public function getMessagesByUserIdForSuperAdmin(int $userId): string
    {
        $currentUser = auth()->user();
        if ($currentUser->role->type !== 'superadmin') {
            throw new UnauthorizedHttpException("You are not authorized to perform this action.");
        }

        $users = $this->getUsersByConversations($userId);

        return view(theme('new.message'), ['users' => $users, 'userId' => $userId]);
    }


    /**
     * @param int $userId
     *
     * @return Collection
     */
    private function getUsersByConversations(int $userId): Collection
    {
        $conversations = Message::where('from_id', $userId)
                                ->orWhere('to_id', $userId)
                                ->orderBy('created_at', 'desc')
                                ->get();

        $users = $conversations->map(function ($conversation) use ($userId) {
            if (intval($conversation->from_id) === $userId) {
                return User::find($conversation->to_id);
            }

            return User::find($conversation->from_id);
        })->unique();

        return $users;
    }

    /**
     * @param Request $request
     *
     * @return string
     * @throws \ErrorException
     */
    public function sendMessage(Request $request): string
    {
        try {
            if (empty($request->message)) {
                throw new \ErrorException('Empty message body');
            }

            $currentUser = auth()->user();
            $fromUserId  = $currentUser->id;
            $toUserId    = intval($request->toUserId);

            // The case when superadmin sends a message instead of Admin
            if (intval($request->insteadOfAdmin) && $currentUser->role->type === 'superadmin') {
                // The id of Admin
                $fromUserId = intval($request->fromUserId);
            }

            $fromUser = User::with('role')->find($fromUserId);
            $toUser   = User::with('role')->find($toUserId);

            if ( ! $fromUser || ! $toUser) {
                throw new \ErrorException('Cannot find user');
            }

            $blocked = Block_user::where([
                ['user_id', '=', $fromUserId],
                ['second_user', "=", $toUserId]
            ])->orwhere([
                ['second_user', '=', $fromUserId],
                ['user_id', "=", $toUserId]
            ])->first();

            if ($blocked) {
                return view('include', ['to_user' => $toUser]);
            }

            $imageName = null;
            if ($request->file) {
                $imageName = time() . '.' . $request->file->getClientOriginalExtension();
                $request->file->move('images/message', $imageName);
            }

            Message::create([
                'from_id'  => $fromUserId,
                'to_id'    => $toUserId,
                'messages' => $request->message,
                'image'    => $imageName ?? null
            ]);

            event(new \App\Events\FormSubmited($toUser));

            if ($toUser->role->type === 'admin' || $toUser->role->type === 'superadmin') {
                register_shutdown_function(function () use ($toUser, $request) {
                    $this->sendMail($toUser, $request->message);
                });
            }

            return view('include', ['to_user' => $toUser]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    /**
     * @param User $user
     * @param $text
     *
     * @return void
     */
    private function sendMail(User $user, $text)
    {
        $data = [
            'from' => env('MAIL_USERNAME'),
            'text' => $text
        ];

        // May need in the feature
//        Mail::to($user->email)->send(new SendSmtpMail($data));
        Mail::to(env('SUPER_ADMIN_MAIL'))->send(new NewMessageMail($data));
    }
}