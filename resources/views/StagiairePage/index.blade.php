<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Stagiaires</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .stagiaire-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .stagiaire-card img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .stagiaire-card h4 {
            margin-bottom: 10px;
        }

        .stagiaire-card p {
            font-size: 1.1em;
        }
    </style>
</head>

<body>
    <div class="container mt-5">


    <div class="d-flex flex-wrap justify-content-end gap-2 m-5">
    <a href="{{ route('stagiaire.edit', $stagiaire->id) }}" class="btn btn-primary">
    <i class="bi bi-pencil me-1"></i> Modifier
    </a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
        </button>
    </form>
</div>

        
        <main class="context-stg">
            <div class="row mb-4 stagiaire-card">
                <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
                    <h4>Image</h4>
                    <img src="{{Storage::url($stagiaire->image) }}" alt="Image de {{ $stagiaire->prenom }}" class="img-fluid">
                </div>
                <div class="col-12 col-md-8">
                    <form>
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <h4>{{ $stagiaire->nom }}</h4>
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <h4>{{ $stagiaire->prenom }}</h4>
                        </div>
                        <div class="mb-3">
                            <label for="date_naissance" class="form-label">Date de Naissance</label>
                            <h4>{{ $stagiaire->date_naissance }}</h4>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <h4>{{ $stagiaire->user->email }}</h4>
                        </div>
                        <div class="mb-3">
                            <label for="date_debut" class="form-label">Date de Début</label>
                            <h4>{{ $stagiaire->date_debut ?? 'Non définie' }}</h4>
                        </div>
                        <div class="mb-3">
                            <a href="{{Storage::url($stagiaire->cv)}}" class="btn btn-primary">CV</a>
                        </div>
                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de Fin</label>
                            <h4>{{ $stagiaire->date_fin ?? 'Non définie' }}</h4>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <p>{{ $stagiaire->description }}</p>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <div>
            <h5>
                @switch($stagiaire->status)
                @case('accepte')
                Votre demande de stage est acceptée.
                @break
                @case('refuse')
                Votre demande de stage est refusée.
                @break
                @case('terminer')
                Votre stage est terminé.
                @break
                @default
                Votre demande de stage est en cours de traitement.
                @endswitch
            </h5>
        </div>
        @if($stagiaire->attestation_de_stage)
        <div class="mb-3">
            <a href="{{Storage::url($stagiaire->attestation_de_stage)}}" class="btn btn-primary">Attestation de Stage</a>
        </div>
        @endif
    </div>
   
</body>

</html>