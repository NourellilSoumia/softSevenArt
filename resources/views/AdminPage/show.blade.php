<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Stagiaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .img {
            width: 100%;
            margin: 65px 3px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary m-3">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        <h1 class="mb-4">Détails du Stagiaire</h1>

        <div class="card">
            <div class="card-header">
                <h2>{{ $stagiaire->nom }} {{ $stagiaire->prenom }}</h2>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        @if ($stagiaire->image)
                        <img src="http://localhost:8000/storage/ucI9W5X57rika1yA4HLGJ7o390oE70hE6BlvpK5w.png" alt="Image de {{ $stagiaire->prenom }}" class="img">
                        @else
                        <span>Aucune image</span>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <!-- <p><strong>ID:</strong> {{ $stagiaire->id }}</p> -->
                        <p><strong>Nom:</strong> {{ $stagiaire->nom }}</p>
                        <p><strong>Prénom:</strong> {{ $stagiaire->prenom }}</p>
                        <p><strong>Date de Naissance:</strong> {{ $stagiaire->date_naissance }}</p>
                        <p><strong>Email:</strong> {{ $stagiaire->user->email }}</p>
                        <p><strong>Date de Début:</strong> {{ $stagiaire->date_debut ?? 'Non définie' }}</p>
                        <p><strong>Date de Fin:</strong> {{ $stagiaire->date_fin ?? 'Non définie' }}</p>
                        <p><strong>Description:</strong> {{ $stagiaire->description ?? 'Aucune description' }}</p>
                        <p><strong>CV:</strong> <a href="{{ Storage::url($stagiaire->cv) }}" target="_blank">Voir le CV</a></p>
                        <p><strong>Attestation de Stage:</strong> @if ($stagiaire->attestation_de_stage)
                            <a href="{{Storage::url($stagiaire->attestation_de_stage)}}" target="_blank">{{ $stagiaire->attestation_de_stage}} </a>
                        </p>
                        @else
                        <span>Aucune Attestation</span>
                        @endif
                        <p><strong>Statut:</strong> <span class="badge bg-warning">{{ $stagiaire->status }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>