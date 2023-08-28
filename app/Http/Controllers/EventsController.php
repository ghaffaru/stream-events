<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index()
    {
        $user_id = 1;

        $user = User::with(['followers', 'subscribers', 'donations','merch_sales'])->findOrFail($user_id);

        $data = [];

        foreach ($user->followers as $follower) {
            $data[] = [
                'name' => $follower->name . ' followed you',
                'read' => $follower->read,
                'date' => $follower->created_at,
                'type' => 'followers',
            ];
        }

        foreach ($user->subscribers as $subscriber) {
            $data[] = [
                'name' => $subscriber->name . '(' . $subscriber->subscription_tier . ') subscribed to you!',
                'read' => $subscriber->read,
                'date' => $subscriber->created_at,
                'type' => 'subscribers',
            ];
        }

        foreach ($user->donations as $donation) {
            $data[] = [
                'name' => $donation->name . ' donated ' . $donation->amount . ' ' . $donation->currency . ' to you! "' . $donation->message . '"' ,
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'message' => $donation->message,
                'read' => $donation->read,
                'date' => $donation->created_at,
                'type' => 'donations',
            ];
        }

        foreach ($user->merch_sales as $merch_sale) {
            $data[] = [
                'name' => $merch_sale->item_name,
                'read' => $merch_sale->read,
                'amount' => $merch_sale->amount,
                'price' => $merch_sale->price,
                'date' => $merch_sale->created_at,
                'type' => 'merch_sales',
            ];
        }


        // Sort the data by date
        usort($data, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        return $data;
    }
}
