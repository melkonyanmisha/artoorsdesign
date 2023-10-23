<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Support\Collection;
use \App\Models\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use phpDocumentor\Parser\Exception;

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
        $users         = $this->getUsers($currentUserId);

        return view(theme('new.message'), ['users' => $users, 'user_id' => $currentUserId]);
    }

    /**
     * Handle the chat page of specific user
     *
     * @param int $user_id
     *
     * @return string
     */
    public function getMessagesByUserIdForSuperAdmin(int $userId): string
    {
        $currentUser = auth()->user();
        if ($currentUser->role->type !== 'superadmin') {
            throw new UnauthorizedHttpException("You are not authorized to perform this action.");
        }

        $users = $this->getUsers($userId);

        return view(theme('new.message'), ['users' => $users, 'userId' => $userId]);
    }


    /**
     * @param int $user_id
     *
     * @return Collection
     */
    private function getUsers(int $user_id): Collection
    {
        $conversations = Message::where('from_id', $user_id)
                                ->orWhere('to_id', $user_id)
                                ->orderBy('created_at', 'desc')
                                ->get();

        $users = $conversations->map(function ($conversation) use ($user_id) {
            if (intval($conversation->from_id) === $user_id) {
                return User::find($conversation->to_id);
            }

            return User::find($conversation->from_id);
        })->unique();

        return $users;
    }
}
