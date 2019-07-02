<?php

namespace JackDou\Management\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JackDou\Management\Models\Server;

class ServersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $servers = Server::where('server_status', 1)
            ->paginate(10);
        return view("management::servers.index", compact('servers'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("management::servers.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $server = new Server();
        $server->server_name = $request->post('server_name');
        $server->server_desc = $request->post('server_desc');
        $server->server_node = $request->post('server_node');
        $server->creator = $request->user()->name;
        $server->save();
        return redirect(route('servers.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $server = Server::find($id);
        return view("management::servers.edit", compact('server', 'id'));
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
        //
        $server = Server::find($id);
        $put = $request->all();
        $server->server_name = $put['server_name'];
        $server->server_desc = $put['server_desc'];
        $server->server_node = $put['server_node'];
        $server->modifier = $request->user()->name;
        $server->save();
        return redirect(route('servers.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
