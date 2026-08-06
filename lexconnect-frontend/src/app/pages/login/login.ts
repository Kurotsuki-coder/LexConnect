import { Component, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './login.html',
  styleUrl: './login.scss',
})
export class Login {
  email = '';
  password = '';
  errorMessage = signal('');

  constructor(private authService: Auth, private router: Router) {}

  onSubmit() {
    this.errorMessage.set('');
    this.authService.login(this.email, this.password).subscribe({
      next: () => this.router.navigate(['/dashboard']),
      error: (err) => {
        const status = err?.status;
        const message = err?.error?.message || 'Identifiants incorrects.';

        if (status === 403) {
          this.router.navigate(['/compte-desactive'], { state: { message } });
        } else {
          this.errorMessage.set(message);
        }
      },
    });
  }
}