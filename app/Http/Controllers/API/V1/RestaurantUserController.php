<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Utilities;
use App\Models\{RestaurantUser, Restaurant, User};
use Exception;

class RestaurantUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'restaurant_id'=> 'required|exists:restaurant,id',
                'member_id'=> 'required|exists:users,id',
                'permission'=> 'required|in:view,edit'
            ]);

            $restaurant = Restaurant::findOrFail($data['restaurant_id']);
            $authUser = $request->user();
            $isOwner = $data['member_id'] === $authUser->id;
            if($isOwner) {
                return response()->json(['message'=> 'Forbidden!'], 403)->header('Content-Type', 'application/json');
            }
            $ru = RestaurantUser::where('member_id', $data['member_id'])->where('restaurant_id', $data['restaurant_id'])->first();
            if($ru) {
                return response()->json(['message'=> 'Member Already exists for that restaurant'], 422)->header('Content-Type', 'application/json');
            }
            $data['owner_email'] = $authUser->email;
            $member = RestaurantUser::create($data);
            return response()->json(['member'=> $member, 'message'=> 'Member Created successfully!'], 200)->header('Content-Type', 'application/json');

        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'permission'=>'required|in:view,edit'
            ]);
            $restaurantUser = RestaurantUser::findOrFail($id);
            $restaurantUser->permission = $request->permission;
            $restaurantUser->save();
            $restaurantUser->fresh();

            return response()->json(['restaurantMember'=> $restaurantUser], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $member = RestaurantUser::findOrFail($id);
            $member->delete();
            return response()->json(['message'=> 'Member deleted successfully!'], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    
    public function getOwnerMembers(Request $request) {
        try {
            $user = $request->user();
            $email = $user->email;
            $members = RestaurantUser::where('owner_email', $email)->get();
            foreach($members as $member):
                $memberUser = User::find($member->member_id);
                $restaurant = Restaurant::find($member->restaurant_id);
                $member['member_email'] = $memberUser->email;
                $member['restaurant_name'] = $restaurant->name;
            endforeach;
            return response()->json(['members'=> $members], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function restaurantsInvitedTo($memberId) {
        try {
            $invitedToRestaurants = RestaurantUser::where('member_id', $memberId)->get();
            $restaurants = [];
            foreach($invitedToRestaurants as $item):
                $restaurant = Restaurant::find($item->restaurant_id);
                $restaurants[] = [
                    'invitedBy'=> $item->owner_email,
                    'permission'=> $item->permission,
                    'restaurant'=> $restaurant->name
                ];
            endforeach;
            return response()->json(['restaurants'=> $restaurants], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}