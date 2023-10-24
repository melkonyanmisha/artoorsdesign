<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Support\Collection;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Http\Request;
use App\Models\Block_user;

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

    public function sendMessage(Request $request): string
    {
        if (empty($request->message)) {
            throw new \ErrorException('Empty message body');
        }

        $currentUser = auth()->user();
        $fromUserId  = $currentUser->id;
        $toUserId    = intval($request->toUserId);

        // The case when superadmin sends a message instead of Admin
        if (boolval($request->insteadOfAdmin) && $currentUser->role->type === 'superadmin') {
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

        //todo@@@ mail functionality
//    \Mail::to($user->email)->send(new SendMail($request->text));

        Message::create([
            'from_id'  => $fromUserId,
            'to_id'    => $toUserId,
            'messages' => $request->message,
            'image'    => $imageName ?? null
        ]);

        event(new \App\Events\FormSubmited($toUser));

        return view('include', ['to_user' => $toUser]);
    }
}
