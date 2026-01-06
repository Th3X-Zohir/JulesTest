# eCourt Bangladesh - Development Roadmap

This document outlines the feature gaps between the current initial scaffold and the complete system specification.

## 1. Authentication & Security (Priority: High)
- [ ] **OTP-Based Login:** Replace/Augment current password auth with SMS-based OTP.
- [ ] **WebAuthn/Passkeys:** Implement FIDO2 login for high security.
- [ ] **Lawyer Verification:** Integration with Bar Association APIs to verify lawyer membership.
- [ ] **Profile Management:** Photo upload, Digital Signature upload/creation.

## 2. Case Management Workflow (Priority: High)
- [x] **Case Submission:** Logic to move cases from `Draft` to `Pending Approval`.
- [x] **Lawyer Assignment:** Workflow for Litigants to assign Lawyers via email invite.
- [x] **Serestadar Review:** Dashboard for court staff to review, approve/reject (with correction notes) cases.
- [ ] **Judge Assignment:** UI for assigning judges (Logic exists in DB).
- [ ] **Case Editing:** Allowing edits only in `Draft` or `Correction Required` states.

## 3. Daily Cause List & Hearings (Priority: Medium)
- [x] **Cause List Generation:** Logic to generate daily lists for each court (Public View).
- [x] **Hearing Management:** Scheduling next hearing dates (Judge/Staff UI).
- [ ] **Order Generation:** Judges interface to write orders and generate PDFs.

## 4. Document Management (Priority: Medium)
- [ ] **PDF Generation:**
    - [x] Arji (Petition)
    - [ ] Order Sheets
    - [ ] Summons
    - [ ] Verdicts
- [ ] **Document Verification:** Public QR code verification endpoint (`/verify/{payload}`).
- [ ] **Digital Signing:** Embedding cryptographic signatures into PDFs.

## 5. Ancillary Modules (Priority: Low)
- [ ] **Witness Management:** Recording witness statements and exhibits.
- [ ] **Written Statements:** Defendant response module.
- [ ] **Applications:** Logic for filing interlocutory applications (Time, Stay, etc.).
- [ ] **E-Qu (Query):** Public query system.
- [ ] **Copyist Department:** Certified copy request workflow.

## 6. Infrastructure & Notifications
- [ ] **Notifications:** SMS and Email dispatchers (Queue based).
- [ ] **Payments:** Shurjopay gateway integration for court fees.
- [ ] **Localization:** Full Bengali translations for frontend and backend.
- [ ] **Audit Logging:** Comprehensive tracking of all user actions.
