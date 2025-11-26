<div class="app-modal" 
     data-delete-title="{{ __('common.delete_title') }}" 
     data-delete-confirm="{{ __('common.delete_confirm') }}"
     data-mark-missed-title="{{ __('appointments.mark_as_missed') }}"
     data-unmark-missed-title="{{ __('appointments.unmark_as_missed') }}"
     data-mark-missed-confirm="{{ __('appointments.mark_missed_confirm') }}"
     data-unmark-missed-confirm="{{ __('appointments.unmark_missed_confirm') }}">
    <div class="app-modal-content">
        <div class="app-modal-title">
            Title
            <button class="btn app-modal-close" data-close>
                <i class="fas fa-close fa-2xl"></i>
            </button>
        </div>

        <div class="app-modal-body">
            Lorem...
        </div>

        <div class="app-modal-footer">
            <button class="btn btn-secondary me-2" data-close>{{ __('common.cancel') }}</button>
            <button class="btn btn-primary app-modal-submit" data-close>{{ __('common.ok') }}</button>
        </div>

    </div>
</div>
