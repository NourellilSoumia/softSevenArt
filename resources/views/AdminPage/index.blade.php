<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Stagiaires</title>
    <!-- Bootstrap CSS + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <h1 class="h3 mb-0 text-center text-md-start">Liste des Stagiaires</h1>
            
            <div class="d-flex flex-wrap justify-content-center gap-2">
              <a href="{{route('admin.add')}}" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Ajouter un stagiaire
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>
        @if ($stagiaires->isEmpty())
        <div class="alert alert-info">Aucun stagiaire trouvé.</div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <!-- <th>Image</th> -->
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th class="d-none d-md-table-cell">Date Naissance</th>
                        <th class="d-none d-lg-table-cell">Email</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                        <th class="text-center">Actions</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stagiaires as $stagiaire)
                    <tr>
                        <td>{{ $stagiaire->id }}</td>
                        <!-- <td>
                            @if ($stagiaire->image)
                            <img src="{{ Storage::url($stagiaire->image)}}" alt="Photo" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td> -->
                        <td>{{ $stagiaire->nom }}</td>
                        <td>{{ $stagiaire->prenom }}</td>
                        <td class="d-none d-md-table-cell">{{ $stagiaire->date_naissance }}</td>
                        <td class="d-none d-lg-table-cell text-truncate" style="max-width: 150px;">{{ $stagiaire->user->email }}</td>
                        <td>{{ $stagiaire->date_debut }}</td>
                        <td>{{ $stagiaire->date_fin }}</td>
                        <td>
                            
                            <div class="d-flex flex-wrap justify-content-center gap-1">
                                <a href="{{ route('admin.show', $stagiaire->id) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                @switch($stagiaire->status)
                                    @case('refuse')
                                    <a href="{{ route('admin.accept', $stagiaire->id) }}" class="btn btn-sm btn-outline-success" title="Accepter">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </a>
                                    @break

                                    @case('accepte')
                                    
                                    @break
                                    @case('termine')
                                    <a href="{{ route('admin.edit', $stagiaire->id) }}" class="btn btn-sm btn-outline-dark" title="Attestation">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </a>
                                    @break

                                    @default
                                    <a href="{{ route('admin.accept', $stagiaire->id) }}" class="btn btn-sm btn-outline-success" title="Accepter">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </a>
                                    <a href="{{ route('admin.refuse', $stagiaire->id) }}" class="btn btn-sm btn-outline-danger" title="Refuser">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </a>
                                @endswitch
                            </div>
                        </td>
                        <td>
                            <span class="badge 
                                @if($stagiaire->status == 'accepte') bg-success
                                @elseif($stagiaire->status == 'refuse') bg-danger
                                @elseif($stagiaire->status == 'termine') bg-secondary
                                @else bg-warning text-dark
                                @endif">
                                {{ $stagiaire->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

      
        @if($stagiaires->hasPages())
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    @if($stagiaires->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">&laquo;</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $stagiaires->previousPageUrl() }}" rel="prev">&laquo;</a>
                    </li>
                    @endif

                    @for ($i = 1; $i <= $stagiaires->lastPage(); $i++)
                    <li class="page-item {{ $stagiaires->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $stagiaires->url($i) }}">{{ $i }}</a>
                    </li>
                    @endfor

                    @if($stagiaires->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $stagiaires->nextPageUrl() }}" rel="next">&raquo;</a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link">&raquo;</span>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
    </div> 
</body>

</html>