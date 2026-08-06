import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AvocatMessagesThreads } from './avocat-messages-threads';

describe('AvocatMessagesThreads', () => {
  let component: AvocatMessagesThreads;
  let fixture: ComponentFixture<AvocatMessagesThreads>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AvocatMessagesThreads],
    }).compileComponents();

    fixture = TestBed.createComponent(AvocatMessagesThreads);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
