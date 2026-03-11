import { TestBed } from '@angular/core/testing';

import { TutorialService } from './tutorial.service';

describe('TutorialService', () => {
  let service: TutorialService;

  beforeEach(() => {
    TestBed.configureTestingModule({}); // MIGRATED: Angular 17 — service tests with no declarations/imports remain unchanged; TestBed.inject() continues to work
    service = TestBed.inject(TutorialService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});