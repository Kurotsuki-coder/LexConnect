import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MessagesLayout } from './messages-layout';

describe('MessagesLayout', () => {
  let component: MessagesLayout;
  let fixture: ComponentFixture<MessagesLayout>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [MessagesLayout],
    }).compileComponents();

    fixture = TestBed.createComponent(MessagesLayout);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
