<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Player;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $players = Player::select('players.id', 'players.name', 'levels.name as level', 'players.score', 'players.suspected', 'players.created_at', 'players.updated_at')
             ->join('levels','players.level_id', '=', 'levels.id')
             ->where('players.id', '>=', $request['start'])
             ->limit($request['n'])
             ->filters($request->all())->get();

        if(count($players)){
            return response()->json($players, 200);
        }

        $response = [
            'success' => false,
            'message' => 'Player list is empty!'
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), 
            array(
                'name' => 'required|string',
                'score' => 'required|numeric|min:0|max:150',
                'suspected' => 'required|string',
            ));

            if ($validator->fails()) {
               return response()->json($validator->errors());
            }

            $player = new Player();
            $player->name = $request['name'];
            $player->level_id = $request['level_id'];
            $player->score = $request['score'];
            $player->suspected = $request['suspected'];
            $player->save();

            $response = [
                'success' => true,
                'message' => 'Player has been created!'
            ];

            return response()->json($response, 200);

        }catch (Exception $e) {            
            return response()->json([  'errors'=> [ ['message' => $e->getMessage()] ] ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            
            $validator = Validator::make($request->all(), 
            array(
                'name' => 'required|string',
                'score' => 'required|numeric|min:0|max:150',
                'suspected' => 'required|string',
            ));

            if ($validator->fails()) {
               return response()->json($validator->errors());
            }

            $player = Player::find($id);

            if($player){

                $player->name = $request['name'];
                $player->level_id = $request['level_id'];                
                $player->score = $request['score'];
                $player->suspected = $request['suspected'];
                $player->save();

                $response = [
                    'success' => true,
                    'message' => 'Player has been updated!'
                ];

                return response()->json($response, 200);            
            }else{
                
                $response = [
                    'success' => false,
                    'message' => 'Player with this id is not exist!'
                ];

                return response()->json($response, 200);
            }

        }catch (Exception $e) {            
            return response()->json([  'errors'=> [ ['message' => $e->getMessage()] ] ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $player = Player::find($id);

            if($player){

                $player->delete();

                $response = [
                    'success' => true,
                    'message' => 'Player has been deleted!'
                ];

                return response()->json($response, 200);            
            }else{

                $response = [
                    'success' => false,
                    'message' => 'Player with this id is not exist!'
                ];

                return response()->json($response, 200);
            }

        }catch (Exception $e) {            
            return response()->json([  'errors'=> [ ['message' => $e->getMessage()] ] ]);
        }
    }
}
