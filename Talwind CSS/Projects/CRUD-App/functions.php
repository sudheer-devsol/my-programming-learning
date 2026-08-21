<?php
/**
 * Renders the <tbody> rows for the students table.
 * Returns plain HTML (not JSON) so the frontend can drop it straight into the DOM.
 */
function renderStudentRows($conn)
{
    $sql = 'SELECT id, name, email, phone, course FROM students ORDER BY id DESC';
    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) === 0) {
        return '<tr><td colspan="5" class="text-center text-gray-400 py-6">No students yet. Add one using the form above.</td></tr>';
    }

    $html = '';
    while ($s = mysqli_fetch_assoc($result)) {
        $id     = (int) $s['id'];
        $name   = htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8');
        $email  = htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8');
        $phone  = htmlspecialchars($s['phone'], ENT_QUOTES, 'UTF-8');
        $course = htmlspecialchars($s['course'], ENT_QUOTES, 'UTF-8');

        $html .= <<<HTML
        <tr class="border-b border-gray-100 hover:bg-gray-50"
            data-id="{$id}" data-name="{$name}" data-email="{$email}" data-phone="{$phone}" data-course="{$course}">
            <td class="py-2 px-3">{$name}</td>
            <td class="py-2 px-3">{$email}</td>
            <td class="py-2 px-3">{$phone}</td>
            <td class="py-2 px-3">{$course}</td>
            <td class="py-2 px-3 text-right space-x-2">
                <button type="button" class="edit-btn text-indigo-600 hover:underline text-sm">Edit</button>
                <button type="button" class="delete-btn text-red-600 hover:underline text-sm">Delete</button>
            </td>
        </tr>
        HTML;
    }

    return $html;
}
