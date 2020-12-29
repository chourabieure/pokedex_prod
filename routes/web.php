<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Team;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'avatar'])->name('welcome');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/avatar', function () {
    return view('avatar');
})->middleware('auth', 'already')->name('avatar');

Route::post('/avatar', function (Request $request) {

    $user = Auth::user();
    $user = User::find($user->id);


    if ($user) {


        $user->avatar = $request->file_name . '.svg';
        $user->avatar_choosen_at = new DateTime();
        $user->save();

        return response()->json('ok', 201);
    } else {
        $error = ['error' => ['message' => "Unauthorized. You must be authentified"]];
        return response()->json($error, 401);
    }
})->middleware('auth');

Auth::routes();

Route::get('/pokemons', function () {

    $pokemons = DB::table('Pokedex')->get();

    //ça c'est HORRIBLE (mais je vois pas trop d'autre facon de le faire)
    foreach ($pokemons as $pokemon) {
        $pokemon->type = array_slice((array)DB::table('Types')->where('id_pok', $pokemon->id)->first(), 2);
        $pokemon->image = "";
    }

    return response()->json($pokemons);
});
Route::get('/pokemons/search/{search}', function ($search) {


    try {
        $pokemons = DB::table('Pokedex')->where('nom_pok', 'like', '%' . $search . '%')->get();

        if ($pokemons) {
            foreach ($pokemons as $pokemon) {
                $pokemon->type = array_slice((array)DB::table('Types')->where('id_pok', $pokemon->id)->first(), 2);
            }

            return response()->json($pokemons);
        } else {
            $error = ['error' => ['message' => "Invalid query"]];
            return response()->json($error, 400);
        }
    } catch (PDOException $e) {
        $error = ['error' => ['message' => "Ressource not found"]];
        return response()->json($error, 404);
    }
});
Route::get('/pokemons/{id}', function ($id) {

    try {
        $pokemon = DB::table('Pokedex')->where('id', $id)->first();
    } catch (PDOException $e) {
        $error = ['error' => ['message' => "Ressource not found"]];
        return response()->json($error, 404);
    }
    if ($pokemon) {
        $pokemon->type = array_slice((array)DB::table('Types')->where('id_pok', $id)->first(), 2);
        $pokemon->image = "";
        $pokemon->description = "";
        $pokemon->stats = array_slice((array)DB::table('Stats')->where('pokemon_id', $id)->first(), 2);
        $pokemon->weaknesses = array_slice((array)DB::table('Weaknesses')->where('pokedex_id', $id)->first(), 2);
        $pokemon->evolutions = ['evolution' => array_slice((array)DB::table('Evolutions')->where('id_pok_base', $id)->first(), 1)];
        return response()->json($pokemon);
    } else {
        $error = ['error' => ['message' => "Invalid query"]];
        return response()->json($error, 400);
    }
});
Route::get('/users', function () {

    $all_users = [];
    $team_complete = [];
    $my_id = Auth::user()->id;
    $users = DB::table('users')->where('id','!=',$my_id)->get();
    foreach ($users as $user) {

        $team = array_slice((array)DB::table('teams')->where('id', $user->team_id)->first(), 1);
        foreach ($team as $pokemon_id) {
            $pokemon =  DB::table('Pokedex')->where('id', $pokemon_id)->first();
            if ($pokemon) {
                $type = array_slice((array)DB::table('Types')->where('id_pok', $pokemon_id)->first(), 2);
                $team_complete[] = ['id' => $pokemon_id, 'nom_pok' => $pokemon->nom_pok, 'type' => $type];
            } else {
                $team_complete[] = null;
            }
        }
        $all_users[] = ['id' => $user->id, 'username' => $user->username, 'profile' => $user->avatar, 'team' => $team_complete];
        $team_complete = [];
    }

    return response()->json($all_users);
});
Route::get('/users/{id}', function ($id) {

    try {
        $users = DB::table('users')->where('id', $id)->first();
    } catch (PDOException $e) {
        $error = ['error' => ['message' => "Ressource not found"]];
        return response()->json($error, 404);
    }
    if ($users) {

        $user = ['id' => $users->id, 'username' => $users->username, 'profile-icon_id' => ''];
        return response()->json($user);
    } else {
        $error = ['error' => ['message' => "Invalid query"]];
        return response()->json($error, 400);
    }
})->where('id', '[0-9]+');
Route::get('/users/search/{search}', function ($search) {

    $user = Auth::user();
    $all_users = [];
    $team_complete = [];
    try {
        $users = DB::table('users')->where('username', 'like', '%' . $search . '%')->where('username', 'not like', $user->username)->get();

        if ($users) {

            foreach ($users as $user) {
                $team = array_slice((array)DB::table('teams')->where('id', $user->team_id)->first(), 1);

                foreach ($team as $pokemon_id) {
                    $pokemon =  DB::table('Pokedex')->where('id', $pokemon_id)->first();
                    if ($pokemon) {
                        $type = array_slice((array)DB::table('Types')->where('id_pok', $pokemon_id)->first(), 2);
                        $team_complete[] = ['id' => $pokemon_id, 'nom_pok' => $pokemon->nom_pok, 'type' => $type];
                    } else {
                        $team_complete[] = null;
                    }
                }
                $all_users[] = [
                    'id' => $user->id,
                    'username' => $user->username,
                    'profile' => $user->avatar,
                    'team' => $team_complete
                ];
                $team_complete = [];
            }

            return response()->json($all_users);
        } else {
            $error = ['error' => ['message' => "Invalid query"]];
            return response()->json($error, 400);
        }
    } catch (PDOException $e) {
        $error = ['error' => ['message' => "Ressource not found"]];
        return response()->json($error, 404);
    }
});
Route::get('/users/me', function () {

    $user = Auth::user();

    if ($user) {
        $user = ['username' => $user->username, 'profile' => $user->avatar];

        return response()->json($user);
    } else {
        $error = ['error' => ['message' => "Unauthorized. You must be authentified"]];
        return response()->json($error, 401);
    }
});
Route::get('/users/me/time', function () {

    $user = Auth::user();

    if ($user) {
        $user = ['time' => $user->got_last_pokemon];
        return response()->json($user);
    } else {
        $error = ['error' => ['message' => "Unauthorized. You must be authentified"]];
        return response()->json($error, 401);
    }
});
Route::get('/users/{id}/team', function ($id) {

    try {
        $user = User::where('id', $id)->first();
    } catch (PDOException $e) {
        $error = ['error' => ['message' => "Ressource not found"]];
        return response()->json($error, 404);
    }
    if ($user) {

        $team = array_slice((array)DB::table('teams')->where('id', $user->team_id)->first(), 1);
        foreach ($team as $k => $v) {
            $nom = DB::table('Pokedex')->where('id', $v)->value('nom_pok');
            $json[$k] = ['id' => $v, 'nom_pok' => $nom, 'type' => array_slice((array)DB::table('Types')->where('id_pok', $v)->first(), 2)];
        }

        return response()->json($json);
    } else {
        $error = ['error' => ['message' => "Invalid query"]];
        return response()->json($error, 400);
    }
})->where('id', '[0-9]+');
Route::get('/users/me/team', function () {

    $user = Auth::user();

    if ($user) {

        $team = array_slice((array)DB::table('teams')->where('id', $user->team_id)->first(), 1);
        foreach ($team as $k => $v) {
            $nom = DB::table('Pokedex')->where('id', $v)->value('nom_pok');
            $json[$k] = ['id' => $v, 'nom_pok' => $nom, 'type' => array_slice((array)DB::table('Types')->where('id_pok', $v)->first(), 2)];
        }

        return response()->json($json);
    } else {
        $error = ['error' => ['message' => "Unauthorized. You must be authentified"]];
        return response()->json($error, 401);
    }
});
Route::post('/users/me', function (Request $request) {

    $user = Auth::user();

    if ($user) {

        $user = User::find($user->id);

        $user->username = $request->username;
        $user->icon_id = $request->icon_id;
        $user->save();
        $user = ['id' => $user->id, 'username' => $user->username, 'profile-icon_id' => ''];

        return response()->json($user);
    } else {
        $error = ['error' => ['message' => "Unauthorized. You must be authentified"]];
        return response()->json($error, 401);
    }
});
Route::post('/release',function(Request $request){

    $user = Auth::user();
    $myTeam = Team::where('id', $user->team_id)->first();

    if ($user && $myTeam) {

        $pokemonToRelease = $request->release_id;

        try {
            if ($myTeam->pokemon_1 == $pokemonToRelease) {
                $myTeam->pokemon_1 = $myTeam->pokemon_2;
                $myTeam->pokemon_2 = $myTeam->pokemon_3;
                $myTeam->pokemon_3 = $myTeam->pokemon_4;
                $myTeam->pokemon_4 = $myTeam->pokemon_5;
                $myTeam->pokemon_5 = $myTeam->pokemon_6;
                $myTeam->pokemon_6 = 0;
            } else if ($myTeam->pokemon_2 == $pokemonToRelease) {
                $myTeam->pokemon_2 = $myTeam->pokemon_3;
                $myTeam->pokemon_3 = $myTeam->pokemon_4;
                $myTeam->pokemon_4 = $myTeam->pokemon_5;
                $myTeam->pokemon_5 = $myTeam->pokemon_6;
                $myTeam->pokemon_6 = 0;
            } else if ($myTeam->pokemon_3 == $pokemonToRelease) {
                $myTeam->pokemon_3 = $myTeam->pokemon_4;
                $myTeam->pokemon_4 = $myTeam->pokemon_5;
                $myTeam->pokemon_5 = $myTeam->pokemon_6;
                $myTeam->pokemon_6 = 0;
            } else if ($myTeam->pokemon_4 == $pokemonToRelease) {
                $myTeam->pokemon_4 = $myTeam->pokemon_5;
                $myTeam->pokemon_5 = $myTeam->pokemon_6;
                $myTeam->pokemon_6 = 0;
            } else if ($myTeam->pokemon_5 == $pokemonToRelease) {
                $myTeam->pokemon_5 = $myTeam->pokemon_6;
                $myTeam->pokemon_6 = 0;
            } else if ($myTeam->pokemon_6 == $pokemonToRelease) {
                $myTeam->pokemon_6 = 0;
            }else{
                return response()->json(['error' => ['message' => "Something went wrong"]], 500);
            }

            $myTeam->save();

            return response()->json('', 201);

        } catch (PDOException $e) {
            $error = ['error' => ['message' => "Could not create ressource"]];
            return response()->json($error, 500);
        }
        
    } else {
        $error = ['error' => ['message' => "Unauthorized. You must be authentified"]];
        return response()->json($error, 401);
    }


});
//exchange with $id user
Route::post('/users/{id}/team', function (Request $request, $id) {

    $user = Auth::user();
    $my_team = Team::where('id', $user->team_id)->first();

    if ($user && $my_team) {

        $me = User::find($user->id);
        $myPokemon_id = $request->myChosen_id;
        $hisPokemon_id = $request->hisChosen_id;

        $target = User::find($id);

        if ($target) {

            try {
                $target_team = Team::where('id', $target->team_id)->first();

                print_r($target_team);
                print_r($hisPokemon_id);
                echo "<br><br>";
                print_r($my_team);
                print_r($myPokemon_id);

                if ($target_team->pokemon_1 == $hisPokemon_id)
                    $target_team->pokemon_1 = $myPokemon_id;
                else if ($target_team->pokemon_2 == $hisPokemon_id)
                    $target_team->pokemon_2 = $myPokemon_id;
                else if ($target_team->pokemon_3 == $hisPokemon_id)
                    $target_team->pokemon_3 = $myPokemon_id;
                else if ($target_team->pokemon_4 == $hisPokemon_id)
                    $target_team->pokemon_4 = $myPokemon_id;
                else if ($target_team->pokemon_5 == $hisPokemon_id)
                    $target_team->pokemon_5 = $myPokemon_id;
                else if ($target_team->pokemon_6 == $hisPokemon_id)
                    $target_team->pokemon_6 = $myPokemon_id;
                else
                    return response()->json(['error' => ['message' => "Something went wrong"]], 500);

                $target_team->save();

                if ($my_team->pokemon_1 == $myPokemon_id)
                    $my_team->pokemon_1 = $hisPokemon_id;
                else if ($my_team->pokemon_2 == $myPokemon_id)
                    $my_team->pokemon_2 = $hisPokemon_id;
                else if ($my_team->pokemon_3 == $myPokemon_id)
                    $my_team->pokemon_3 = $hisPokemon_id;
                else if ($my_team->pokemon_4 == $myPokemon_id)
                    $my_team->pokemon_4 = $hisPokemon_id;
                else if ($my_team->pokemon_5 == $myPokemon_id)
                    $my_team->pokemon_5 = $hisPokemon_id;
                else if ($my_team->pokemon_6 == $myPokemon_id)
                    $my_team->pokemon_6 = $hisPokemon_id;
                else
                    return response()->json(['error' => ['message' => "Something went wrong"]], 500);

                $my_team->save();

                return response()->json('', 201);

            } catch (PDOException $e) {
                $error = ['error' => ['message' => "Could not create ressource"]];
                return response()->json($error, 500);
            }
        } else {
            $error = ['error' => ['message' => "Invalid query"]];
            return response()->json($error, 400);
        }
    } else {
        $error = ['error' => ['message' => "Unauthorized. You must be authentified"]];
        return response()->json($error, 401);
    }
})->where('id', '[0-9]+');

Route::patch('/users/resetTime', function (Request $request) {
    $user = Auth::user();

    if ($user) {
        $date = (new DateTime())->format('Y-m-d H:i:s');
        $user = User::find($user->id);
        $user->got_last_pokemon = $date;

        $user->save();
        if ($user) {
            $team = Team::where('id', $user->team_id)->first();

            if ($team->pokemon_6) {
                $full = true;
            } else {
                $full = false;
                if (!$team->pokemon_1) {
                    $team->pokemon_1 = rand(1, 151);
                } else if (!$team->pokemon_2) {
                    $team->pokemon_2 = rand(1, 151);
                } else if (!$team->pokemon_3) {
                    $team->pokemon_3 = rand(1, 151);
                } else if (!$team->pokemon_4) {
                    $team->pokemon_4 = rand(1, 151);
                } else if (!$team->pokemon_5) {
                    $team->pokemon_5 = rand(1, 151);
                } else if (!$team->pokemon_6) {
                    $team->pokemon_6 = rand(1, 151);
                    $full = true;
                }
                $team->save();
            }
            $user = ['time' => $user->got_last_pokemon, 'team_full' => $full];
            return response()->json($user);
        }
        $error = ['error' => ['message' => "Something went wrong"]];
        return response()->json($error, 500);
    } else {
        $error = ['error' => ['message' => "Unauthorized. You must be authentified"]];
        return response()->json($error, 401);
    }
});
Route::get('{path}', function ($path) {

    if ($path == 'logout') {
        Auth::logout();
        return redirect()->route('login');
    }
    return view('welcome');
})->where('path', '([A-z\d-\/_.]+)?')->middleware(['auth', 'avatar']);


// Route::get('/pokemons', 'PokemonController@index');
// Route::post('/tasks', 'ContentController@store');
