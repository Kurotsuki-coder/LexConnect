import { Component, EventEmitter, Input, Output, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-motif-modal',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './motif-modal.html',
  styleUrl: './motif-modal.scss',
})
export class MotifModal {
  @Input() visible = false;
  @Input() title = 'Motif requis';
  @Input() actionLabel = 'Confirmer';
  @Input() dangerAction = false;

  @Output() confirmed = new EventEmitter<string>();
  @Output() cancelled = new EventEmitter<void>();

  motif = '';
  error = signal('');

  onConfirm() {
    const trimmed = this.motif.trim();
    if (trimmed.length < 10) {
      this.error.set('Merci de préciser un motif d\'au moins 10 caractères.');
      return;
    }
    this.error.set('');
    this.confirmed.emit(trimmed);
    this.motif = '';
  }

  onCancel() {
    this.motif = '';
    this.error.set('');
    this.cancelled.emit();
  }
}