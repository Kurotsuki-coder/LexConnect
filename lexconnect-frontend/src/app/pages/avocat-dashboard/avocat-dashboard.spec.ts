import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AvocatDashboard } from './avocat-dashboard';

describe('AvocatDashboard', () => {
  let component: AvocatDashboard;
  let fixture: ComponentFixture<AvocatDashboard>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AvocatDashboard],
    }).compileComponents();

    fixture = TestBed.createComponent(AvocatDashboard);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
