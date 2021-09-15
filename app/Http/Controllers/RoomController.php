<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function GuzzleHttp\Promise\all;

class RoomController extends Controller
{
    function index()
    {
        return Room::all();
    }

    function searchByID($id)
    {
        return Room::find($id);
    }

    function getAllRoomsBetweenDates(Request $request)
    {
        if (!isset($request->api_token)) {
            return response('Error, no API Token given', 401);
        }

        $user = \App\Models\User::query()->where("api_token", "=", $request->api_token)->get();

        if ($user->count() == 0) {
            return response('Error, no user bound to this API Token', 401);
        }

        $user = $user[0];

        if (!isset($request->startdate) || !isset($request->enddate)) {
            return response('Error, please provide startdate and enddate.', 401);
        }

        $startdate = $request->startdate;
        $enddate = $request->enddate;

        $starttime = strtotime($startdate); // or your date as well
        $endtime = strtotime($enddate);
        $datediff =  $endtime - $starttime;

        $daysBetween = round($datediff / (60 * 60 * 24));

        $daysArray = [];

        $rooms = Room::all();

        $allRooms = [];
        $reservations = [];

        foreach ($rooms as $room) {
            $room->booked = false;
            $allRooms[$room->id] = $room;
        }

        for ($i = 0; $i < $daysBetween; $i++) {

            $date = new DateTime($request->startdate);
            $date->add(new DateInterval('P' . $i .'D'));

            $reservations = Reservation::query()->where("date", "=", $date->format('Y-m-d'))->get();

            foreach ($reservations as $reservation) {
                $reservation->user = User::find($reservation->user_id);
                $allRooms[$reservation->room_id]->reservation = $reservation;
                $allRooms[$reservation->room_id]->booked = true;
            }

            $daysArray[$date->format('Y-m-d')] = $allRooms;
        }

        return $daysArray;
    }

    function getFreeRoomsBetweenDates(Request $request)
    {
        if (!isset($request->startdate) || !isset($request->enddate)) {
            return response('Error, please provide startdate and enddate.', 401);
        }

        $startdate = $request->startdate;
        $enddate = $request->enddate;

        $reservations = Reservation::query()->whereBetween("date", [$startdate, $enddate])->get("room_id");
        $rooms = Room::all();

        $freeRooms = [];

        foreach ($rooms as $room) {
            foreach ($reservations as $reservation) {
                if ($reservation->room_id == $room->id) {
                    $room->booked = true;
                }
            }
            if (!$room->booked) {
                $freeRooms[] = $room;
            }
        }

        return $freeRooms;
    }
}
