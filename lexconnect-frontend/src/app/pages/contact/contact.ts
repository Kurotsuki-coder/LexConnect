import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { Api } from '../../services/api';

@Component({
  selector: 'app-contact',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './contact.html',
  styleUrl: './contact.scss',
})
export class Contact implements OnInit {
  nom = '';
  email = '';
  sujet = '';
  message = '';
  envoye = signal(false);
  errorMessage = signal('');

  backLink = signal('/');
  backLabel = signal('← Retour à l\'accueil');

  constructor(private api: Api) {}

  ngOnInit() {
    this.api.get<any>('/api/user').subscribe({
      next: (u) => {
        switch (u.role) {
          case 'citoyen':
            this.backLink.set('/dashboard/citoyen');
            this.backLabel.set('← Retour au tableau de bord');
            break;
          case 'avocat':
            this.backLink.set('/dashboard/avocat');
            this.backLabel.set('← Retour au tableau de bord');
            break;
          case 'admin':
            this.backLink.set('/dashboard/admin');
            this.backLabel.set('← Retour au tableau de bord');
            break;
        }
      },
      error: () => {
        // Pas connecté → reste sur "/" par défaut
      },
    });
  }

  onSubmit() {
    this.errorMessage.set('');
    this.api.post('/api/contact', { nom: this.nom, email: this.email, sujet: this.sujet, message: this.message }).subscribe({
      next: () => this.envoye.set(true),
      error: () => this.errorMessage.set("Une erreur est survenue, réessaie plus tard."),
    });
  }
}