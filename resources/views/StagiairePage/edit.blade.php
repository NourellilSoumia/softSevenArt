<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les Données du Stagiaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .form-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }
        .profile-image-container {
            position: relative;
            margin-bottom: 20px;
            text-align: center;
        }
        .profile-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #007bff;
        }
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            margin-bottom: 15px;
        }
        .file-input-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .back-link {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('stagiaire.index', $stagiaire->id) }}" class="back-link btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>

        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="form-container">
                    <h1 class="text-center mb-4">Modifier vos Données</h1>
                    
                    <form id="stagiaire-form" method="post" action="{{ route('stagiaire.update', $stagiaire->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="profile-image-container">
                            <img src="http://localhost:8000/storage/ucI9W5X57rika1yA4HLGJ7o390oE70hE6BlvpK5w.png" 
                                 alt="Image de {{ $stagiaire->prenom }}" 
                                 class="profile-image" 
                                 id="current-image">
                            
                            <div class="file-input-wrapper mt-3">
                                <button class="btn btn-primary">
                                    <i class="bi bi-upload me-2"></i>Changer l'image
                                </button>
                                <input type="file" name="image" id="image-input" 
                                       accept="image/*" 
                                       class="form-control" 
                                       onchange="previewImage(event)">
                            </div>
                            
                            <img id="image-preview" 
                                 src="" 
                                 alt="Aperçu de l'image" 
                                 style="display:none; max-width: 200px; max-height: 200px; object-fit: cover;">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" id="nom" name="nom" 
                                       class="form-control" 
                                       value="{{ $stagiaire->nom }}"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" id="prenom" name="prenom" 
                                       class="form-control" 
                                       value="{{ $stagiaire->prenom }}"
                                       required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_naissance" class="form-label">Date de Naissance</label>
                                <input type="date" id="date_naissance" name="date_naissance" 
                                       class="form-control" 
                                       value="{{ $stagiaire->date_naissance }}"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="tel" id="telephone" name="telephone" 
                                       class="form-control" 
                                       value="{{ $stagiaire->telephone }}"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" 
                                   class="form-control" 
                                   value="{{ $stagiaire->user->email }}"
                                   required>
                        </div>

                        @if($stagiaire->status == 'en attente' || $stagiaire->status == 'refuse')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_debut" class="form-label">Date de Début</label>
                                <input type="date" id="date_debut" name="date_debut" 
                                       class="form-control" 
                                       value="{{ $stagiaire->date_debut }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_fin" class="form-label">Date de Fin</label>
                                <input type="date" id="date_fin" name="date_fin" 
                                       class="form-control" 
                                       value="{{ $stagiaire->date_fin }}">
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" 
                                      class="form-control" 
                                      rows="4">{{ $stagiaire->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="cv" class="form-label">CV (PDF, DOC, DOCX)</label>
                            <input type="file" id="cv" name="cv" 
                                   class="form-control" 
                                   accept=".pdf,.doc,.docx">
                        </div>


                        <!-- ################################################# -->
                        <div class="card border-info mb-3">
                                <div class="card-header bg-info text-white">
                                    Modification du Mot de Passe
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                                        <input type="password" class="form-control" id="current_password" placeholder="Mot de passe actuel">
                                    </div>

                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                        <input type="password" class="form-control" id="new_password" placeholder="Nouveau mot de passe">
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                                        <input type="password" class="form-control" id="confirm_password" placeholder="Confirmez le mot de passe">
                                    </div>

                                    <div class="alert alert-secondary" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Laissez vide si vous ne souhaitez pas modifier le mot de passe
                                    </div>
                                </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');
            const currentImage = document.getElementById('current-image');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    currentImage.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>