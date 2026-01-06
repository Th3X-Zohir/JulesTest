# eCourt Bangladesh - Development Roadmap

This document outlines the feature gaps between the current initial scaffold and the complete system specification.

## 1. Authentication & Security (Priority: High)
- [ ] **OTP-Based Login:** Replace/Augment current password auth with SMS-based OTP.
- [ ] **WebAuthn/Passkeys:** Implement FIDO2 login for high security.
- [ ] **Lawyer Verification:** Integration with Bar Association APIs to verify lawyer membership.
- [ ] **Profile Management:** Photo upload, Digital Signature upload/creation.

## 2. Case Management Workflow (Priority: High)
- [ ] **Case Submission:** Logic to move cases from `Draft` to `Pending Approval`.
- [ ] **Lawyer Assignment:** Workflow for Litigants to assign Lawyers before submission.
- [ ] **Serestadar Review:** Dashboard for court staff to review, approve/reject (with correction notes) cases.
- [ ] **Judge Assignment:** Logic to assign cases to judges automatically or manually.
- [ ] **Case Editing:** Allowing edits only in `Draft` or `Correction Required` states.

## 3. Daily Cause List & Hearings (Priority: Medium)
- [ ] **Cause List Generation:** Logic to generate daily lists for each court.
- [ ] **Hearing Management:** Scheduling next hearing dates, recording attendance.
- [ ] **Order Generation:** Judges interface to write orders and generate PDFs.

## 4. Document Management (Priority: Medium)
- [ ] **PDF Generation:**
    - [x] Arji (Petition) - *Planned for Phase 2*
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

---

## Immediate Next Steps (Phase 2 Plan)
1. **Case Submission Logic:** Enable users to submit drafts.
2. **Serestadar Approval:** Allow court staff to approve cases and generate Case Numbers.
3. **Role-Based Dashboards:** Distinct views for Litigants vs. Staff.
4. **Basic PDF:** Generate the Case Petition (Arji) PDF.
