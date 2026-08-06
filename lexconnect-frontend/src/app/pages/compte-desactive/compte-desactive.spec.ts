import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CompteDesactive } from './compte-desactive';

describe('CompteDesactive', () => {
  let component: CompteDesactive;
  let fixture: ComponentFixture<CompteDesactive>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CompteDesactive],
    }).compileComponents();

    fixture = TestBed.createComponent(CompteDesactive);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
