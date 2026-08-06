import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RegisterAvocat } from './register-avocat';

describe('RegisterAvocat', () => {
  let component: RegisterAvocat;
  let fixture: ComponentFixture<RegisterAvocat>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [RegisterAvocat],
    }).compileComponents();

    fixture = TestBed.createComponent(RegisterAvocat);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
