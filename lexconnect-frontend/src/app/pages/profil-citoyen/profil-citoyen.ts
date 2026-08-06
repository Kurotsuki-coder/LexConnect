import { Component, OnInit, signal, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { Api } from '../../services/api';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-profil-citoyen',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './profil-citoyen.html',
  styleUrl: './profil-citoyen.scss',
})
export class ProfilCitoyen implements OnInit {
  nom = '';
  prenom = '';
  email = '';
  telephone = '';
  region = '';

  loading = signal(true);
  editing = signal(false);
  savedMessage = signal('');

  // Section mot de passe (dépliable dans le même formulaire)
  showPasswordFields = signal(false);
  currentPassword = '';
  newPassword = '';
  newPasswordConfirmation = '';
  passwordError = signal('');

  // Modale de suppression de compte (remplace window.confirm)
  showDeleteModal = signal(false);
  deleteConfirmText = '';
  deleteError = signal('');

  regions = [
    'Dakar', 'Diourbel', 'Fatick', 'Kaffrine', 'Kaolack', 'Kédougou',
    'Kolda', 'Louga', 'Matam', 'Saint-Louis', 'Sédhiou', 'Tambacounda',
    'Thiès', 'Ziguinchor',
  ];

  constructor(
    private api: Api,
    private authService: Auth,
    private router: Router,
    private cd: ChangeDetectorRef
  ) {}

  ngOnInit() {
    this.load();
  }

  load() {
    this.loading.set(true);
    this.api.get<any>('/api/user').subscribe({
      next: (user) => {
        this.nom = user.nom ?? '';
        this.prenom = user.prenom ?? '';
        this.email = user.email ?? '';
        this.telephone = user.telephone ?? '';
        this.region = user.region ?? '';
        this.loading.set(false);
        this.cd.detectChanges();
      },
      error: () => this.loading.set(false),
    });
  }

  get initials(): string {
    return (this.prenom.charAt(0) + this.nom.charAt(0)).toUpperCase();
  }

  startEdit() {
    this.editing.set(true);
  }

  cancelEdit() {
    this.editing.set(false);
    this.showPasswordFields.set(false);
    this.currentPassword = '';
    this.newPassword = '';
    this.newPasswordConfirmation = '';
    this.passwordError.set('');
    this.load();
  }

  togglePasswordFields() {
    this.showPasswordFields.update((v) => !v);
    this.passwordError.set('');
  }

  save() {
    this.passwordError.set('');

    if (this.showPasswordFields()) {
      if (!this.currentPassword || !this.newPassword || !this.newPasswordConfirmation) {
        this.passwordError.set('Remplis tous les champs du mot de passe, ou annule cette section.');
        return;
      }
      if (this.newPassword !== this.newPasswordConfirmation) {
        this.passwordError.set('Les deux nouveaux mots de passe ne correspondent pas.');
        return;
      }
    }

    this.api
      .put('/api/profile', { nom: this.nom, prenom: this.prenom, telephone: this.telephone, region: this.region })
      .subscribe({
        next: () => {
          if (this.showPasswordFields()) {
            this.savePassword();
          } else {
            this.finishSave();
          }
        },
        error: (err) => console.error('Erreur modification profil :', err),
      });
  }

  private savePassword() {
    this.api
      .put('/api/profile/password', {
        current_password: this.currentPassword,
        new_password: this.newPassword,
        new_password_confirmation: this.newPasswordConfirmation,
      })
      .subscribe({
        next: () => this.finishSave(),
        error: (err) => {
          this.passwordError.set(
            err?.error?.errors?.current_password?.[0] || err?.error?.message || 'Erreur lors du changement de mot de passe.'
          );
        },
      });
  }

  private finishSave() {
    this.editing.set(false);
    this.showPasswordFields.set(false);
    this.currentPassword = '';
    this.newPassword = '';
    this.newPasswordConfirmation = '';
    this.savedMessage.set('Profil mis à jour.');
    setTimeout(() => this.savedMessage.set(''), 3000);
    this.load();
  }

  // --- Suppression de compte : modale custom ---

  openDeleteModal() {
    this.deleteConfirmText = '';
    this.deleteError.set('');
    this.showDeleteModal.set(true);
  }

  closeDeleteModal() {
    this.showDeleteModal.set(false);
    this.deleteConfirmText = '';
    this.deleteError.set('');
  }

  confirmDeleteAccount() {
    if (this.deleteConfirmText.trim().toUpperCase() !== 'SUPPRIMER') {
      this.deleteError.set('Tape SUPPRIMER pour confirmer.');
      return;
    }

    this.api.delete('/api/profile').subscribe({
      next: () => this.router.navigate(['/']),
      error: (err) => {
        this.deleteError.set(err?.error?.message || 'Erreur lors de la suppression du compte.');
        console.error('Erreur suppression compte :', err);
      },
    });
  }
}