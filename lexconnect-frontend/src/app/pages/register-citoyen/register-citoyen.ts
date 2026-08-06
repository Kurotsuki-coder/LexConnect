import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-register-citoyen',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './register-citoyen.html',
  styleUrl: './register-citoyen.scss',
})
export class RegisterCitoyen {
  nom = '';
  prenom = '';
  email = '';
  telephone = '';
  region = '';
  password = '';
  password_confirmation = '';
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
      .register('citoyen', {
        nom: this.nom,
        prenom: this.prenom,
        email: this.email,
        telephone: this.telephone,
        region: this.region,
        password: this.password,
        password_confirmation: this.password_confirmation,
      })
      .subscribe({
        next: () => this.router.navigate(['/dashboard']),
        error: (err) => {
          this.errorMessage = err?.error?.message || 'Une erreur est survenue.';
        },
      });
  }
}