# Button & Pagination Consistency Guide

## Overview

This guide documents the standardized button and pagination system implemented across the LBDairy application to ensure visual consistency and improved user experience.

## Action Button Standards

### User-Friendly Design
All action buttons now include both icons and text labels to make them more user-friendly and accessible. This ensures users can easily understand what each button does without having to guess from icons alone.

### Button Container
All action buttons should be wrapped in the `.action-buttons` container:

```html
<div class="action-buttons">
    <!-- Action buttons go here -->
</div>
```

### Button Classes

#### Base Action Button
```html
<button class="btn-action btn-action-[type] btn-action-[size]">
    <i class="fas fa-[icon]"></i>
    <span>Button Text</span>
</button>
```

#### Action Button Types

| Type | Class | Color | Usage |
|------|-------|-------|-------|
| View | `btn-action-view` | Blue (Info) | View details, read-only actions |
| Edit | `btn-action-edit` | Blue (Primary) | Edit, modify actions |
| Delete | `btn-action-delete` | Red (Danger) | Delete, remove actions |
| Print | `btn-action-print` | Green (Success) | Print, export actions |
| Approve | `btn-action-approve` | Green (Success) | Approval actions |
| Reject | `btn-action-reject` | Yellow (Warning) | Rejection actions |
| Toggle | `btn-action-toggle` | Gray (Secondary) | Toggle status actions |
| Reset | `btn-action-reset` | Yellow (Warning) | Reset password, reset actions |
| Details | `btn-action-details` | Blue (Info) | Show detailed information |
| Report | `btn-action-report` | Red (Danger) | Report issues, problems |
| Investigate | `btn-action-investigate` | Yellow (Warning) | Investigation actions |
| Flag | `btn-action-flag` | Red (Danger) | Flag, mark as problematic |
| Ledger | `btn-action-ledger` | Yellow (Warning) | View ledger, financial records |

#### Button Sizes

| Size | Class | Dimensions | Usage |
|------|-------|------------|-------|
| Small | `btn-action-sm` | 32px height | Compact tables, mobile |
| Default | (none) | 36px height | Standard usage |
| Large | `btn-action-lg` | 44px height | Prominent actions |
| Icon Only | `btn-action-icon` | Square | Icon-only buttons (for very compact spaces) |
| Text Only | `btn-action-text` | Standard | Text-only buttons (for accessibility) |

### Examples

#### Standard Action Buttons
```html
<!-- View Details -->
<button class="btn-action btn-action-view" onclick="viewDetails(id)" title="View Details">
    <i class="fas fa-eye"></i>
    <span>View</span>
</button>

<!-- Edit -->
<button class="btn-action btn-action-edit" onclick="editItem(id)" title="Edit">
    <i class="fas fa-edit"></i>
    <span>Edit</span>
</button>

<!-- Delete -->
<button class="btn-action btn-action-delete" onclick="confirmDelete(id)" title="Delete">
    <i class="fas fa-trash"></i>
    <span>Delete</span>
</button>
```

#### Button with Text
```html
<button class="btn-action btn-action-approve" onclick="approveItem(id)">
    <i class="fas fa-check"></i>
    <span>Approve</span>
</button>
```

## Pagination Standards

### Standard Pagination
Use the pagination component for consistent pagination across the system:

```php
<x-pagination :paginator="$items" />
```

### Manual Pagination HTML
If you need to create pagination manually:

```html
<div class="pagination-container">
    <div class="pagination-info">
        Showing 1 to 10 of 100 results
    </div>
    
    <div class="pagination-nav">
        <!-- Previous -->
        <a href="?page=1" class="pagination-btn prev">
            <i class="fas fa-chevron-left pagination-icon"></i>
        </a>
        
        <!-- Page Numbers -->
        <a href="?page=1" class="pagination-btn page-number">1</a>
        <span class="pagination-btn active page-number">2</span>
        <a href="?page=3" class="pagination-btn page-number">3</a>
        
        <!-- Next -->
        <a href="?page=3" class="pagination-btn next">
            <i class="fas fa-chevron-right pagination-icon"></i>
        </a>
    </div>
</div>
```

### DataTables Pagination
DataTables pagination is automatically styled to match the system standards. The CSS overrides ensure consistency.

## Implementation Examples

### Table Action Column
```html
<td>
    <div class="action-buttons">
        <button class="btn-action btn-action-view" onclick="viewDetails('{{ $item->id }}')" title="View Details">
            <i class="fas fa-eye"></i>
            <span>View</span>
        </button>
        <button class="btn-action btn-action-edit" onclick="editItem('{{ $item->id }}')" title="Edit">
            <i class="fas fa-edit"></i>
            <span>Edit</span>
        </button>
        <button class="btn-action btn-action-delete" onclick="confirmDelete('{{ $item->id }}')" title="Delete">
            <i class="fas fa-trash"></i>
            <span>Delete</span>
        </button>
    </div>
</td>
```

### Card Header Actions
```html
<div class="card-header">
    <h6><i class="fas fa-users"></i> Users</h6>
    <div class="action-buttons">
        <button class="btn-action btn-action-print btn-action-sm" onclick="printReport()" title="Print Report">
            <i class="fas fa-print"></i>
            <span>Print</span>
        </button>
        <button class="btn-action btn-action-approve" onclick="showAddModal()">
            <i class="fas fa-plus"></i>
            <span>Add User</span>
        </button>
    </div>
</div>
```

### Modal Footer Actions
```html
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    <button type="submit" class="btn-action btn-action-approve">
        <i class="fas fa-save"></i>
        <span>Save Changes</span>
    </button>
</div>
```

## Migration Guide

### Converting Old Buttons

#### Before (Old Style)
```html
<div class="btn-group" role="group">
    <button class="btn btn-info btn-sm" onclick="viewDetails(id)">
        <i class="fas fa-eye"></i>
    </button>
    <button class="btn btn-primary btn-sm" onclick="editItem(id)">
        <i class="fas fa-edit"></i>
    </button>
    <button class="btn btn-danger btn-sm" onclick="deleteItem(id)">
        <i class="fas fa-trash"></i>
    </button>
</div>
```

#### After (New Style)
```html
<div class="action-buttons">
    <button class="btn-action btn-action-view" onclick="viewDetails(id)" title="View Details">
        <i class="fas fa-eye"></i>
        <span>View</span>
    </button>
    <button class="btn-action btn-action-edit" onclick="editItem(id)" title="Edit">
        <i class="fas fa-edit"></i>
        <span>Edit</span>
    </button>
    <button class="btn-action btn-action-delete" onclick="deleteItem(id)" title="Delete">
        <i class="fas fa-trash"></i>
        <span>Delete</span>
    </button>
</div>
```

### Button Type Mapping

| Old Class | New Class | Purpose |
|-----------|-----------|---------|
| `btn-info` | `btn-action-view` | View actions |
| `btn-primary` | `btn-action-edit` | Edit actions |
| `btn-danger` | `btn-action-delete` | Delete actions |
| `btn-success` | `btn-action-approve` | Approval actions |
| `btn-warning` | `btn-action-reject` | Rejection actions |
| `btn-secondary` | `btn-action-toggle` | Toggle actions |

## CSS Classes Reference

### Action Button Classes
- `.action-buttons` - Container for action buttons
- `.btn-action` - Base action button class
- `.btn-action-[type]` - Specific action type styling
- `.btn-action-[size]` - Size variants (sm, lg, icon)
- `.btn-action-icon` - Icon-only button variant

### Pagination Classes
- `.pagination-container` - Main pagination wrapper
- `.pagination-info` - Results information
- `.pagination-nav` - Navigation buttons container
- `.pagination-btn` - Individual pagination button
- `.pagination-btn.active` - Current page
- `.pagination-btn.disabled` - Disabled state
- `.pagination-btn.prev` - Previous button
- `.pagination-btn.next` - Next button
- `.pagination-btn.first` - First page button
- `.pagination-btn.last` - Last page button
- `.pagination-btn.page-number` - Page number button

## Best Practices

1. **Always use the `.action-buttons` container** for grouping action buttons
2. **Include both icons and text labels** for better user experience
3. **Use meaningful button text** that clearly describes the action
4. **Include meaningful titles** for accessibility
5. **Use appropriate button types** based on the action
6. **Consistent icon usage** - use FontAwesome icons
7. **Responsive design** - buttons adapt to mobile screens
8. **Accessibility** - include screen reader text where needed
9. **Loading states** - use `.btn-loading` class for async actions

## Browser Support

The button and pagination system supports:
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Performance

- CSS uses modern features like CSS Grid and Flexbox
- Minimal JavaScript dependencies
- Optimized for mobile performance
- Efficient CSS selectors

## Troubleshooting

### Common Issues

1. **Buttons not styling correctly**
   - Ensure `button-consistency.css` is loaded
   - Check for conflicting CSS classes

2. **Pagination not working**
   - Verify the pagination component is included
   - Check Laravel pagination setup

3. **Mobile responsiveness issues**
   - Test on actual mobile devices
   - Check viewport meta tag

### Debug Mode

Add this CSS to debug button layouts:
```css
.action-buttons {
    border: 1px solid red;
}
.btn-action {
    border: 1px solid blue;
}
```

## Future Enhancements

- Dark mode support
- Additional button types
- Animation customization
- Advanced pagination features
- Accessibility improvements
