<?php

namespace App\Http\Controllers\Web;

use App\DataTransferObjects\Artist\ArtistDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Artist\CreateRequest;
use App\Http\Requests\Web\Artist\UpdateRequest;
use App\Models\Artist;
use App\Services\Artist\ArtistService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ArtistController extends Controller
{
    public function __construct(
        protected ArtistService $artistService
    )
    {
    }

    public function index(): View
    {
        $artists = Artist::orderBy('id', 'DESC')->get();
        return view('app.artists.index',compact('artists'));
    }

    public function create(): View
    {
        return view('app.artists.create');
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $artist = $this->artistService->create(ArtistDto::fromWebRequest($request));
        try{
            $this->artistService->saveImage($artist, $request->file('artist_image'));
        }catch (\Exception $exception){
            $request->session()->flash('error', $exception->getMessage());
            return redirect()->back();
        }
        $request->session()->flash('success', 'Successfully Saved');
        return redirect(route('artists.edit', $artist->id));
    }

    public function edit($id): View
    {
        $artist = Artist::with('image')->find($id);
        return view('app.artists.edit',compact('artist'));
    }

    public function update(Artist $artist, UpdateRequest $request): RedirectResponse
    {
        $artist = $this->artistService->update($artist, ArtistDto::fromWebRequest($request));
        if($request->hasFile('artist_image')){
            $artist->loadMissing('image');
            try{
                $this->artistService->saveImage(
                    $artist,
                    $request->file('artist_image'),
                    !empty($artist->image)
                );
            }catch (\Exception $exception){
                $request->session()->flash('error', $exception->getMessage());
                return redirect()->back();
            }
        }
        $request->session()->flash('success', 'Successfully Updated');
        return redirect()->back();
    }
}
