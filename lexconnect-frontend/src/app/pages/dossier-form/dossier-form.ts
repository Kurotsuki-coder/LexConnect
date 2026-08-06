import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { Api } from '../../services/api';

@Component({
  selector: 'app-dossier-form',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './dossier-form.html',
  styleUrl: './dossier-form.scss',
})
export class DossierForm {
  motif = '';
  description = '';
  budget: number | null = null;
  niveau_urgence = 2;
  errorMessage = '';

  constructor(private api: Api, private router: Router) {}

  onSubmit() {
    this.errorMessage = '';
    this.api
      .post('/api/citoyen/dossiers', {
        motif: this.motif,
        description: this.description,
        budget: this.budget,
        niveau_urgence: this.niveau_urgence,
      })
      .subscribe({
        next: () => this.router.navigate(['/dashboard/citoyen']),
        error: (err: any) => { this.errorMessage = err?.error?.message || 'Erreur lors de la création.'; },
      });
  }
}