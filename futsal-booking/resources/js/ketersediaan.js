import TomSelect from 'tom-select';

document.addEventListener('DOMContentLoaded', function() {
    const lapanganSelect = document.getElementById('lapangan-select');
    
    if (lapanganSelect) {
        new TomSelect(lapanganSelect, {
            create: false,
            sortField: {
                field: 'text',
                direction: 'asc'
            },
            maxOptions: 50,
            maxItems: 1,
            allowEmptyOption: true,
            placeholder: '— Pilih lapangan —',
            onInitialize: function() {
                lapanganSelect.style.display = 'none';
            },
            render: {
                option: function(data, escape) {
                    const imageUrl = data.image || '';
                    const category = data.category || '';
                    const categoryLabel = category === 'internasional' ? 'Internasional' : 'Standar';
                    
                    if (imageUrl) {
                        return '<div class="option" style="display: flex; align-items: center; gap: 10px;">' +
                            '<img src="' + escape(imageUrl) + '" ' +
                                'style="width: 32px; height: 32px; max-width: 32px; max-height: 32px; object-fit: cover; border-radius: 6px; flex-shrink: 0; display: block;" ' +
                                'onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'block\';" ' +
                                'alt="">' +
                            '<div style="width: 32px; height: 32px; background: #4b5563; border-radius: 6px; flex-shrink: 0; display: none;"></div>' +
                            '<div style="flex: 1;">' +
                                '<div style="font-weight: 500;">' + escape(data.text) + '</div>' +
                                '<div style="font-size: 11px; opacity: 0.7; margin-top: 2px;">' + escape(categoryLabel) + '</div>' +
                            '</div>' +
                        '</div>';
                    } else {
                        return '<div class="option" style="display: flex; align-items: center; gap: 10px;">' +
                            '<div style="width: 32px; height: 32px; background: #4b5563; border-radius: 6px; flex-shrink: 0;"></div>' +
                            '<div style="flex: 1;">' +
                                '<div style="font-weight: 500;">' + escape(data.text) + '</div>' +
                                '<div style="font-size: 11px; opacity: 0.7; margin-top: 2px;">' + escape(categoryLabel) + '</div>' +
                            '</div>' +
                        '</div>';
                    }
                },
                item: function(data, escape) {
                    const imageUrl = data.image || '';
                    const category = data.category || '';
                    const categoryLabel = category === 'internasional' ? 'Internasional' : 'Standar';
                    
                    if (imageUrl) {
                        return '<div class="item" style="display: flex; align-items: center; gap: 8px;">' +
                            '<img src="' + escape(imageUrl) + '" ' +
                                'style="width: 24px; height: 24px; max-width: 24px; max-height: 24px; object-fit: cover; border-radius: 4px; flex-shrink: 0; display: block;" ' +
                                'onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'block\';" ' +
                                'alt="">' +
                            '<div style="width: 24px; height: 24px; background: #4b5563; border-radius: 4px; flex-shrink: 0; display: none;"></div>' +
                            '<div style="flex: 1;">' +
                                '<div>' + escape(data.text) + '</div>' +
                                '<div style="font-size: 10px; opacity: 0.7;">' + escape(categoryLabel) + '</div>' +
                            '</div>' +
                        '</div>';
                    } else {
                        return '<div class="item" style="display: flex; align-items: center; gap: 8px;">' +
                            '<div style="width: 24px; height: 24px; background: #4b5563; border-radius: 4px; flex-shrink: 0;"></div>' +
                            '<div style="flex: 1;">' +
                                '<div>' + escape(data.text) + '</div>' +
                                '<div style="font-size: 10px; opacity: 0.7;">' + escape(categoryLabel) + '</div>' +
                            '</div>' +
                        '</div>';
                    }
                },
                optgroup_header: function(data, escape) {
                    return '<div class="optgroup-header">' + escape(data.label) + '</div>';
                }
            }
        });
    }
});
