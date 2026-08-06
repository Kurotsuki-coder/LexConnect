import { Component, OnInit, signal } from '@angular/core';
import { RouterLink, RouterLinkActive, RouterOutlet, Router } from '@angular/router';
import { Auth } from '../../services/auth';
import { Api } from '../../services/api';

@Component({
  selector: 'app-admin-layout',
  standalone: true,
  imports: [RouterLink, RouterLinkActive, RouterOutlet],
  templateUrl: './admin-layout.html',
  styleUrl: './admin-layout.scss',
})
export class AdminLayout implements OnInit {
  initials = signal('..');
  nom = signal('');

  constructor(private authService: Auth, private api: Api, private router: Router) {}

  ngOnInit() {
    this.api.get<any>('/api/user').subscribe({
      next: (u) => {
        this.initials.set(`${u.prenom.charAt(0)}${u.nom.charAt(0)}`.toUpperCase());
        this.nom.set(`${u.prenom} ${u.nom}`);
      },
    });
  }

  logout() {
    this.authService.logout().subscribe({ next: () => this.router.navigate(['/login']) });
  }
}