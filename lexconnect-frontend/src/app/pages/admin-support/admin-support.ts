import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Api } from '../../services/api';

interface ContactMsg {
  id_contact: number;
  nom: string;
  email: string;
  sujet: string;
  message: string;
  statut: string;
  created_at: string;
}

@Component({
  selector: 'app-admin-support',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './admin-support.html',
  styleUrl: './admin-support.scss',
})
export class AdminSupport implements OnInit {
  contacts = signal<ContactMsg[]>([]);
  loading = signal(true);

  constructor(private api: Api) {}

  ngOnInit() {
    this.load();
  }

  load() {
    this.loading.set(true);
    this.api.get<ContactMsg[]>('/api/admin/contacts').subscribe({
      next: (data) => { this.contacts.set(data); this.loading.set(false); },
      error: () => this.loading.set(false),
    });
  }

  traiter(id: number) {
    this.api.patch(`/api/admin/contacts/${id}/traiter`, {}).subscribe({ next: () => this.load() });
  }

  supprimer(id: number) {
    if (!confirm('Supprimer ce message ?')) return;
    this.api.delete(`/api/admin/contacts/${id}`).subscribe({ next: () => this.load() });
  }
}