@if (Gate::allows('edit-html-blocks'))
    <html-manager inline-template>
        <div id="html-manager">
            <ul>
                <li @click="applyChanges" title="Apply changes">
                    Apply changes
                </li>
            </ul>
        </div>
    </html-manager>
@endif