import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MotifModal } from './motif-modal';

describe('MotifModal', () => {
  let component: MotifModal;
  let fixture: ComponentFixture<MotifModal>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [MotifModal],
    }).compileComponents();

    fixture = TestBed.createComponent(MotifModal);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
