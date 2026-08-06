import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AvocatLayout } from './avocat-layout';

describe('AvocatLayout', () => {
  let component: AvocatLayout;
  let fixture: ComponentFixture<AvocatLayout>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AvocatLayout],
    }).compileComponents();

    fixture = TestBed.createComponent(AvocatLayout);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
