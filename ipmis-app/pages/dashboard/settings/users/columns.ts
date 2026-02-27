// columns.ts
import type { ColumnDef } from '@tanstack/vue-table'
import { ArrowUpDown, Eye, PenLine, Trash } from 'lucide-vue-next'
import { h } from 'vue'
import Button from '~/components/ui/button/Button.vue'

export const getColumns = (onView: (user: User) => void, onDelete: (user: User) => void, onEdit: (user: User) => void): ColumnDef<User>[] => [
    // {
    //     accessorKey: 'id',
    //     header: ({ column }) =>
    //         h(Button, {
    //             variant: 'ghost',
    //             onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
    //         }, () => ['id', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
    //     cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('id')),
    // },
    {
        accessorKey: 'name',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('name')),
        meta: {
            filterable: true,
        },
    },
    {
        accessorKey: 'email',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Email', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('email')),
        meta: {
            filterable: true,
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const user = row.original

            return h('div', { class: 'flex items-center gap-2' }, [
                // h(Button, {
                //     variant: 'newsecondary',
                //     class: 'flex items-center gap-2',
                //     onClick: () => onView(user),
                // }, {
                //     default: () => [
                //         h(Eye, { class: 'w-4 h-4' }),
                //         '',
                //     ],
                // }),

                h(Button, {
                    variant: 'newsecondary',
                    class: 'flex items-center gap-2',
                    onClick: () => onEdit(user),
                }, {
                    default: () => [
                        h(PenLine, { class: 'w-4 h-4' }),
                        '',
                    ],
                }),
                h(Button, {
                    variant: 'newsecondary',
                    class: 'flex items-center gap-2 text-red-600',
                    onClick: () => onDelete(user),
                }, {
                    default: () => [
                        h(Trash, { class: 'w-4 h-4' }),
                        '',
                    ],
                }),
            ])
        },
    },
]
