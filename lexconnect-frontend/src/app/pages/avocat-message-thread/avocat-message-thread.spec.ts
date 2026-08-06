import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AvocatMessageThread } from './avocat-message-thread';

describe('AvocatMessageThread', () => {
  let component: AvocatMessageThread;
  let fixture: ComponentFixture<AvocatMessageThread>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AvocatMessageThread],
    }).compileComponents();

    fixture = TestBed.createComponent(AvocatMessageThread);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
