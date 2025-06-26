<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une attestation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .profile-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
.img{
    width: 200px;
    height: 200px;
}
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }

        .upload-section {
            border: 2px dashed #dee2e6;
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
            background-color: #f8f9fa;
        }

        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Ajouter une attestation de stage</h1>
            <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="card-title mb-0">{{ $stagiaire->nom }} {{ $stagiaire->prenom }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if ($stagiaire->image)
                        <img src="http://localhost:8000/storage/ucI9W5X57rika1yA4HLGJ7o390oE70hE6BlvpK5w.png" alt="Image de {{ $stagiaire->prenom }}" class="img">
                        @else
                        <div class="text-center p-5 bg-light mb-4 rounded">
                            <i class="bi bi-person-circle" style="font-size: 8rem; color: #6c757d;"></i>
                            <p class="mt-3">Aucune image de profil</p>
                        </div>
                        @endif

                        <div class="mb-3">
                            <h5>Statut actuel</h5>
                            @switch($stagiaire->status)
                            @case('accepte')
                            <span class="badge bg-success status-badge">Accepté</span>
                            @break
                            @case('refuse')
                            <span class="badge bg-danger status-badge">Refusé</span>
                            @break
                            @case('termine')
                            <span class="badge bg-danger status-badge">termine</span>
                            @break
                            @default
                            <span class="badge bg-warning status-badge">En attente</span>
                            @endswitch
                        </div>

                        <div class="list-group">
                            <!-- <div class="list-group-item">
                                <strong>ID:</strong> {{ $stagiaire->id }}
                            </div> -->
                            <div class="list-group-item">
                                <strong>Date de Naissance:</strong> {{ $stagiaire->date_naissance }}
                            </div>
                            <div class="list-group-item">
                                <strong>Email:</strong> {{ $stagiaire->user->email }}
                            </div>
                            <div class="list-group-item">
                                <strong>CV:</strong>
                                <a href="{{ Storage::url($stagiaire->cv) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="bi bi-file-earmark-pdf"></i> Voir le CV
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <h4 class="mb-3">Informations de stage</h4>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Date de Début:</strong> {{ $stagiaire->date_debut ?? 'Non définie' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Date de Fin:</strong> {{ $stagiaire->date_fin ?? 'Non définie' }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Description du stage</h5>
                            <p>{{ $stagiaire->description ?? 'Aucune description' }}</p>
                        </div>

                        <hr>

                        <div class="mt-4">
                            <h4>Ajouter une attestation de stage</h4>
                            <form action="{{ route('admin.update', $stagiaire->id) }}" method="POST" class="upload-section" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="attestation_stage" class="form-label">Fichier d'attestation (PDF, DOC, DOCX - max 5MB)</label>
                                    <input type="file" class="form-control" name="attestation_stage" id="attestation_stage" required>
                                    <div class="form-text">Sélectionnez le document d'attestation à télécharger.</div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-cloud-upload"></i> Télécharger l'attestation
                                    </button>
                                </div>
                            </form>

                            @if($stagiaire->attestation_de_stage)
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle"></i> Une attestation existe déjà pour ce stagiaire. Le téléchargement d'une nouvelle attestation remplacera l'ancienne.
                                <div class="mt-2">
                                    <a href="{{ Storage::url($stagiaire->attestation_stage) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark-text"></i> Voir l'attestation actuelle
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>