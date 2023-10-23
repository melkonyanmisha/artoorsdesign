<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Support\Collection;
use \App\Models\User;

class MessageController extends Controller
{
    /**
     * Handle the chat page of the current user
     *
     * @return string
     */
    public function index(): string
    {
        $user_id = auth()->id();
        $users   = $this->getUsers($user_id);

        return view(theme('new.message'), ['users' => $users, 'user_id' => $user_id]);
    }

    /**
     * Handle the chat page of specific user
     *
     * @param int $user_id
     *
     * @return string
     */
    public function getMessagesByUserId(int $user_id): string
    {
        $users = $this->getUsers($user_id);

        return view(theme('new.message'), ['users' => $users, 'user_id' => $user_id]);
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
