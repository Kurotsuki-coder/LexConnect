import { Component, OnInit, signal, computed } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive, RouterOutlet, ActivatedRoute } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';

interface Thread {
  id_dossier: number;
  nom: string;
  prenom: string;
  dernier_message: string | null;
  heure: string | null;
}

@Component({
  selector: 'app-messages-layout',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink, RouterLinkActive, RouterOutlet],
  templateUrl: './messages-layout.html',
  styleUrl: './messages-layout.scss',
})
export class MessagesLayout implements OnInit {
  threads = signal<Thread[]>([]);
  loading = signal(true);
  hasSelection = signal(false);
  query = signal('');

  filteredThreads = computed(() => {
    const q = this.query().toLowerCase().trim();
    const list = [...this.threads()].sort((a, b) => {
      if (!a.heure) return 1;
      if (!b.heure) return -1;
      return new Date(b.heure).getTime() - new Date(a.heure).getTime();
    });
    if (!q) return list;
    return list.filter((t) => `${t.prenom} ${t.nom}`.toLowerCase().includes(q));
  });

  constructor(private api: Api, private route: ActivatedRoute) {}

  ngOnInit() {
    this.load();
    this.hasSelection.set(!!this.route.firstChild);
    this.route.url.subscribe(() => this.hasSelection.set(!!this.route.firstChild));
  }

  load() {
    this.loading.set(true);
    this.api.get<Thread[]>('/api/messages/threads').subscribe({
      next: (data) => { this.threads.set(data); this.loading.set(false); },
      error: () => this.loading.set(false),
    });
  }

  initials(t: Thread): string {
    return `${t.prenom.charAt(0)}${t.nom.charAt(0)}`.toUpperCase();
  }

  formatHeure(heure: string | null): string {
    if (!heure) return '';
    const date = new Date(heure);
    const now = new Date();
    const isToday = date.toDateString() === now.toDateString();
    if (isToday) return date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    return date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' });
  }
}