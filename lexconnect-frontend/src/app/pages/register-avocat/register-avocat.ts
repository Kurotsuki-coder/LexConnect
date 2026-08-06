import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-register-avocat',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './register-avocat.html',
  styleUrl: './register-avocat.scss',
})
export class RegisterAvocat {
  nom = '';
  prenom = '';
  email = '';
  telephone = '';
  region = '';
  password = '';
  password_confirmation = '';
  numero_barre = '';
  errorMessage = '';

  regions = [
    'Dakar', 'Diourbel', 'Fatick', 'Kaffrine', 'Kaolack', 'Kédougou',
    'Kolda', 'Louga', 'Matam', 'Saint-Louis', 'Sédhiou', 'Tambacounda',
    'Thiès', 'Ziguinchor',
  ];

  constructor(private authService: Auth, private router: Router) {}

  onSubmit() {
    this.errorMessage = '';
    this.authService
      .register('avocat', {
        nom: this.nom,
        prenom: this.prenom,
        email: this.email,
        telephone: this.telephone,
        region: this.region,
        password: this.password,
        password_confirmation: this.password_confirmation,
        numero_barre: this.numero_barre,
      })
      .subscribe({
        next: () => this.router.navigate(['/inscription-en-attente']),
        error: (err) => {
          this.errorMessage = err?.error?.message || 'Une erreur est survenue.';
        },
      });
  }
}