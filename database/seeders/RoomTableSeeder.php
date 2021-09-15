<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $room = new Room();
        $room->number = 1;
        $room->floor = 1;
        $room->description = "Vergader ruimte, Eerste verdieping, 20 personen.";
        $room->save();

        $room = new Room();
        $room->number = 2;
        $room->floor = 1;
        $room->description = "Vergader ruimte, Eerste verdieping, 6 personen.";
        $room->save();

        $room = new Room();
        $room->number = 3;
        $room->floor = 1;
        $room->description = "Vergader ruimte, Eerste verdieping, 2 personen.";
        $room->save();

        $room = new Room();
        $room->number = 1;
        $room->floor = 2;
        $room->description = "kantoor ruimte, Tweede verdieping, 5 personen.";
        $room->save();

        $room = new Room();
        $room->number = 2;
        $room->floor = 2;
        $room->description = "kantoor ruimte, Tweede verdieping, 7 personen.";
        $room->save();

        $room = new Room();
        $room->number = 3;
        $room->floor = 2;
        $room->description = "Management ruimte, Tweede verdieping, 10 personen.";
        $room->save();
    }
}
