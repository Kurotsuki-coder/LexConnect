import { Component, OnInit, signal, computed } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Api } from '../../services/api';
import { MotifModal } from '../../components/motif-modal/motif-modal';

interface Utilisateur {
  id_utilisateur: number;
  nom: string;
  prenom: string;
  email: string;
  role: string;
  statut: string;
}

@Component({
  selector: 'app-admin-utilisateurs',
  standalone: true,
  imports: [CommonModule, MotifModal],
  templateUrl: './admin-utilisateurs.html',
  styleUrl: './admin-utilisateurs.scss',
})
export class AdminUtilisateurs implements OnInit {
  utilisateurs = signal<Utilisateur[]>([]);
  loading = signal(true);
  filtre = signal<'tous' | 'citoyen' | 'avocat'>('tous');

  filtered = computed(() => {
    const f = this.filtre();
    if (f === 'tous') return this.utilisateurs();
    return this.utilisateurs().filter((u) => u.role === f);
  });

  // Gestion de la modale
  modalVisible = signal(false);
  modalTitle = signal('');
  modalAction = signal('');
  private pendingUserId: number | null = null;
  private pendingAction: 'suspendre' | 'supprimer' | null = null;

  constructor(private api: Api) {}

  ngOnInit() {
    this.load();
  }

  load() {
    this.loading.set(true);
    this.api.get<Utilisateur[]>('/api/admin/utilisateurs').subscribe({
      next: (data) => { this.utilisateurs.set(data); this.loading.set(false); },
      error: () => this.loading.set(false),
    });
  }

  setFiltre(f: 'tous' | 'citoyen' | 'avocat') {
    this.filtre.set(f);
  }

  suspendre(id: number) {
    this.pendingUserId = id;
    this.pendingAction = 'suspendre';
    this.modalTitle.set('Suspendre ce compte');
    this.modalAction.set('Suspendre');
    this.modalVisible.set(true);
  }

  supprimer(id: number) {
    this.pendingUserId = id;
    this.pendingAction = 'supprimer';
    this.modalTitle.set('Désactiver définitivement ce compte');
    this.modalAction.set('Désactiver');
    this.modalVisible.set(true);
  }

  reactiver(id: number) {
    this.api.patch(`/api/admin/utilisateurs/${id}/reactiver`, {}).subscribe({ next: () => this.load() });
  }

  onModalConfirm(motif: string) {
    if (!this.pendingUserId || !this.pendingAction) return;

    if (this.pendingAction === 'suspendre') {
      this.api.patch(`/api/admin/utilisateurs/${this.pendingUserId}/suspendre`, { motif }).subscribe({ next: () => this.load() });
    } else if (this.pendingAction === 'supprimer') {
      this.api.delete(`/api/admin/utilisateurs/${this.pendingUserId}`, { motif }).subscribe({ next: () => this.load() });
    }

    this.closeModal();
  }

  onModalCancel() {
    this.closeModal();
  }

  private closeModal() {
    this.modalVisible.set(false);
    this.pendingUserId = null;
    this.pendingAction = null;
  }

  initials(u: Utilisateur): string {
    return `${u.prenom.charAt(0)}${u.nom.charAt(0)}`.toUpperCase();
  }
}