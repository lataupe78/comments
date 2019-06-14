@extends('layouts.app')

@section('content')


<div class="jumbotron mb-5">
    <div class="container">
        <h1>{{ config('app.name', 'Laravel Commentaires') }}</h1>
    </div>
</div>

<div class="container">

    <div class="card mb-5">
        <div class="card-body">
            <h2>
                <a href="{{ route('posts.index') }}">Liste des Posts</a>
            </h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <p>Portage sous laravel 5.8 du tutoriel de grafikart sur les commentaires imbriqués</p>

            <ul class="list-group mb-4">
                <li class="list-group-item">
                    <a href="https://www.grafikart.fr/tutoriels/commentaire-vuejs-laravel-part1-761">Module de commentaires : Création de l'API</a>
                </li>
                <li class="list-group-item">
                    <a href="https://www.grafikart.fr/tutoriels/commentaire-vuejs-laravel-part2-762">Module de commentaires : Frontend</a>
                </li>
            </ul>

            <p>On en profite pour se perfectionner avec le TDD et les bonnes pratiques Laravel ( FormRequest) et github</p>

            <p>
                <a href="https://github.com/lataupe78/comments">Dépot Github</a>
            </p>
        </div>
    </div>
</div>

@endsection
