import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MessagesThreads } from './messages-threads';

describe('MessagesThreads', () => {
  let component: MessagesThreads;
  let fixture: ComponentFixture<MessagesThreads>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [MessagesThreads],
    }).compileComponents();

    fixture = TestBed.createComponent(MessagesThreads);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
