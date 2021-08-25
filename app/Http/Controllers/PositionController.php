<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePositionRequest;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StorePositionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function store(StorePositionRequest $request, $shopping_list)
    {
        Position::create($request->validated() + ['shopping_list_id' => $shopping_list]);

        return back(201)->with('message', __('custom.global.messages.successfully_save'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Position $position
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Position $position)
    {
        Position::check_permission($position);

        return view('positions.edit', compact('position'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StorePositionRequest $request
     * @param Position $position
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StorePositionRequest $request, Position $position)
    {
        Position::check_permission($position);

        $position->update($request->validated());

        return back()->with('message', __('custom.global.messages.successfully_save'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Position $position
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Position $position)
    {
        Position::check_permission($position);

        $position->delete();

        return back()
            ->with(
                'message',
                __('custom.global.messages.successfully_delete')
                . '<a href="'.route('positions.restore', $position->id).'" class="text-red-500 ml-2 md:ml-12">
                    <strong>Ups! Cofnij!</strong>
                    </a>');
    }

    public function restore($position_id)
    {
        $position = Position::withTrashed()->findOrFail($position_id);
        if ($position && $position->trashed()) {
            $position->restore();
        }

        return back()->with('message', __('custom.global.messages.successfully_restore'));
    }

    public function mark_as_done(Position $position)
    {
        Position::check_permission($position);

        $position->update(['is_done' => true]);

        return back();
    }

    public function unmark_as_done(Position $position)
    {
        Position::check_permission($position);

        $position->update(['is_done' => false]);

        return back();
    }
}
