import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';

interface Proposition {
  id_demande: number;
  message: string | null;
  statut_demande: string;
  avocat: {
    id_avocat: number;
    utilisateur: { nom: string; prenom: string };
    profil: { specialites: string | null } | null;
  };
}

interface Dossier {
  id_dossier: number;
  motif: string;
  description: string;
  budget: number | null;
  statut_dossier: string;
  niveau_urgence: number;
  demandes: Proposition[];
}

@Component({
  selector: 'app-dossier-detail',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './dossier-detail.html',
  styleUrl: './dossier-detail.scss'
})
export class DossierDetail implements OnInit {
  dossier = signal<Dossier | null>(null);
  loading = signal(true);
  editing = signal(false);
  errorMessage = signal('');
  avisEnvoye = signal(false);

  motif = '';
  description = '';
  budget: number | null = null;
  niveau_urgence = 2;
  note = 5;
  commentaire = '';

  private id!: string;

  constructor(
    private api: Api,
    private route: ActivatedRoute,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.id = this.route.snapshot.paramMap.get('id')!;
    this.load();
  }

  load(): void {
    this.loading.set(true);
    this.api.get<Dossier>(`/api/citoyen/dossiers/${this.id}`).subscribe({
      next: (d) => {
        this.dossier.set(d);
        this.motif = d.motif;
        this.description = d.description;
        this.budget = d.budget;
        this.niveau_urgence = d.niveau_urgence;
        this.loading.set(false);
      },
      error: () => this.loading.set(false)
    });
  }

  startEdit(): void {
    this.editing.set(true);
  }

  cancelEdit(): void {
    this.editing.set(false);
  }

  saveEdit(): void {
    this.api.put<Dossier>(`/api/citoyen/dossiers/${this.id}`, {
      motif: this.motif,
      description: this.description,
      budget: this.budget,
      niveau_urgence: this.niveau_urgence
    }).subscribe({
      next: (d) => {
        this.dossier.set(d);
        this.editing.set(false);
      },
      error: (e) => this.errorMessage.set(e?.error?.message || 'Erreur modification.')
    });
  }

  supprimer(): void {
    if (!confirm('Supprimer définitivement ce dossier ?')) return;

    this.api.delete(`/api/citoyen/dossiers/${this.id}`).subscribe({
      next: () => this.router.navigate(['/dashboard/citoyen']),
      error: (e) => this.errorMessage.set(e?.error?.message || 'Erreur suppression.')
    });
  }

  accepterProposition(id: number): void {
    if (!confirm('Accepter cette proposition ?')) return;

    this.api.patch(`/api/citoyen/demandes/${id}/accepter`, {}).subscribe({
      next: () => this.load(),
      error: (e) => this.errorMessage.set(e?.error?.message || 'Erreur.')
    });
  }

  refuserProposition(id: number): void {
    this.api.patch(`/api/citoyen/demandes/${id}/refuser`, {}).subscribe({
      next: () => this.load()
    });
  }

  envoyerAvis(): void {
    this.api.post('/api/citoyen/avis', {
      id_dossier: this.id,
      note: this.note,
      commentaire: this.commentaire
    }).subscribe({
      next: () => this.avisEnvoye.set(true),
      error: (e) => this.errorMessage.set(e?.error?.message || 'Erreur avis.')
    });
  }

  retourDashboard(): void {
    this.router.navigate(['/dashboard/citoyen']);
  }
}