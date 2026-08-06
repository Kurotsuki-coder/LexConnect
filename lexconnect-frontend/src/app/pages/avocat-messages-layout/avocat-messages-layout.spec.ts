import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AvocatMessagesLayout } from './avocat-messages-layout';

describe('AvocatMessagesLayout', () => {
  let component: AvocatMessagesLayout;
  let fixture: ComponentFixture<AvocatMessagesLayout>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AvocatMessagesLayout],
    }).compileComponents();

    fixture = TestBed.createComponent(AvocatMessagesLayout);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
