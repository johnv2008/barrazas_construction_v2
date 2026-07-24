/*
 * Barraza's Construction — admin panel behavior.
 * Vanilla JS, no dependencies.
 */
(function () {
  'use strict';

  var sidebar = document.querySelector('[data-admin-sidebar]');
  var sidebarToggle = document.querySelector('[data-sidebar-toggle]');
  var sidebarClose = document.querySelector('[data-sidebar-close]');

  function openSidebar() {
    if (!sidebar) return;
    sidebar.setAttribute('data-open', 'true');
  }

  function closeSidebar() {
    if (!sidebar) return;
    sidebar.setAttribute('data-open', 'false');
  }

  if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
  if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);

  var userMenuButton = document.querySelector('[data-user-menu-button]');
  var userMenuPanel = document.querySelector('[data-user-menu-panel]');

  if (userMenuButton && userMenuPanel) {
    userMenuButton.addEventListener('click', function (event) {
      event.stopPropagation();
      var isOpen = userMenuPanel.getAttribute('data-open') === 'true';
      userMenuPanel.setAttribute('data-open', isOpen ? 'false' : 'true');
      userMenuButton.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
    });

    document.addEventListener('click', function (event) {
      if (!userMenuPanel.contains(event.target)) {
        userMenuPanel.setAttribute('data-open', 'false');
        userMenuButton.setAttribute('aria-expanded', 'false');
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        userMenuPanel.setAttribute('data-open', 'false');
        userMenuButton.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // Generic confirmation modal for destructive actions. Any element
  // with [data-confirm] triggers the shared modal instead of the
  // native confirm() dialog, and submits the associated form only on
  // explicit confirmation.
  var confirmModal = document.querySelector('[data-confirm-modal]');
  var confirmMessage = confirmModal ? confirmModal.querySelector('[data-confirm-message]') : null;
  var confirmAcceptBtn = confirmModal ? confirmModal.querySelector('[data-confirm-accept]') : null;
  var confirmCancelBtn = confirmModal ? confirmModal.querySelector('[data-confirm-cancel]') : null;
  var pendingForm = null;

  document.querySelectorAll('[data-confirm]').forEach(function (trigger) {
    trigger.addEventListener('click', function (event) {
      if (!confirmModal) return;
      event.preventDefault();
      pendingForm = trigger.closest('form');
      if (confirmMessage) {
        confirmMessage.textContent = trigger.getAttribute('data-confirm') || 'Are you sure?';
      }
      confirmModal.setAttribute('data-open', 'true');
      if (confirmAcceptBtn) confirmAcceptBtn.focus();
    });
  });

  function closeConfirmModal() {
    if (!confirmModal) return;
    confirmModal.setAttribute('data-open', 'false');
    pendingForm = null;
  }

  if (confirmAcceptBtn) {
    confirmAcceptBtn.addEventListener('click', function () {
      if (pendingForm) pendingForm.submit();
      closeConfirmModal();
    });
  }

  if (confirmCancelBtn) {
    confirmCancelBtn.addEventListener('click', closeConfirmModal);
  }
})();
