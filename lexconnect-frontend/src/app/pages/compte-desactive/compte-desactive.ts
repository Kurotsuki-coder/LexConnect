import { Component } from '@angular/core';
import { Router, RouterLink } from '@angular/router';


@Component({
  selector: 'app-compte-desactive',
  standalone: true,
  imports: [RouterLink],
  templateUrl: './compte-desactive.html',
  styleUrl: './compte-desactive.scss',
})
export class CompteDesactive {
  message: string;

  constructor(private router: Router) {
    const nav = this.router.getCurrentNavigation();
    this.message = (nav?.extras?.state as any)?.['message'] || "Ce compte n'est plus accessible.";
  }
}