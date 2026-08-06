import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [],
  templateUrl: './dashboard.html',
  styleUrl: './dashboard.scss',
})
export class Dashboard implements OnInit {
  constructor(private authService: Auth, private router: Router) {}

  ngOnInit() {
    this.authService.fetchUser().subscribe({
      next: (user) => {
        switch (user.role) {
          case 'citoyen':
            this.router.navigate(['/dashboard/citoyen']);
            break;
          case 'avocat':
            this.router.navigate(['/dashboard/avocat']);
            break;
          case 'admin':
            this.router.navigate(['/dashboard/admin']);
            break;
          default:
            this.router.navigate(['/login']);
        }
      },
      error: () => this.router.navigate(['/login']),
    });
  }
}