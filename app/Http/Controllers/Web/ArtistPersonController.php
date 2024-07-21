<?php

namespace App\Http\Controllers\Web;

use App\DataTransferObjects\Artist\ArtistPersonDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Artist\CreateArtistPersonRequest;
use App\Http\Requests\Web\Artist\UpdateArtistPersonRequest;
use App\Models\Artist;
use App\Models\ArtistPerson;
use App\Services\Artist\ArtistService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ArtistPersonController extends Controller
{
    public function __construct(
        protected ArtistService $artistService
    )
    {
    }

    public function index(): View
    {
        $artist_persons = ArtistPerson::orderBy('id', 'DESC')->get();
        return view('app.artist_person.index', compact('artist_persons'));
    }

    public function create(): View
    {
        $artists = Artist::orderBy('id', 'DESC')->get();
        return view('app.artist_person.create',compact('artists'));
    }

    public function store(CreateArtistPersonRequest $request): RedirectResponse
    {
        $artistPerson = $this->artistService->createPerson(
            ArtistPersonDto::fromWebRequest($request)
        );

        if($request->hasFile('artist_person_image')){
            try{
                $this->artistService->saveImage(
                    $artistPerson,
                    $request->file('artist_person_image')
                );
            }catch (\Exception $exception){
                $request->session()->flash('error', $exception->getMessage());
                return redirect()->back();
            }
        }
        $request->session()->flash('success', 'Successfully Saved');
        return redirect()->route('artist_person_edit', $artistPerson->id);
    }

    public function edit($id) : View
    {
        $artists = Artist::orderBy('id', 'DESC')->get();
        $artist_person = ArtistPerson::find($id);
        return view('app.artist_person.edit',compact('artists','artist_person'));
    }

    public function update(ArtistPerson $person, UpdateArtistPersonRequest $request): RedirectResponse
    {
        $artistPerson = $this->artistService->updatePerson(
            $person,
            ArtistPersonDto::fromWebRequest($request)
        );

        if($request->hasFile('artist_person_image')){
            $artistPerson->loadMissing('image');
            try{
                $this->artistService->saveImage(
                    $artistPerson,
                    $request->file('artist_person_image'),
                    !empty($artistPerson->image)
                );
            }catch (\Exception $exception){
                $request->session()->flash('error', $exception->getMessage());
                return redirect()->back();
            }
        }
        $request->session()->flash('success', 'Successfully Updated!');
        return redirect()->back();
    }
}
