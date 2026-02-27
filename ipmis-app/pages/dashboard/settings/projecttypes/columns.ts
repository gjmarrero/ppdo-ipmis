// columns.ts
import type { ColumnDef } from '@tanstack/vue-table'
import { ArrowUpDown, Eye, PenLine, Trash } from 'lucide-vue-next'
import { h } from 'vue'
import Button from '~/components/ui/button/Button.vue'

import {
    AlertDialog,
    AlertDialogTrigger,
    AlertDialogContent,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogCancel,
    AlertDialogAction,
} from '@/components/ui/alert-dialog'

export const getColumns = (onView: (project_type: ProjectType) => void, onDelete: (project_type: ProjectType) => void, onEdit: (project_type: ProjectType) => void): ColumnDef<ProjectType>[] => [
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
            }, () => ['Project Type', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('name')),
        meta: {
            filterable: true,
        }
    },
    {
        accessorKey: 'project_type_code',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Project Type Code', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('project_type_code')),
        meta: {
            filterable: true,
        }
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const project_type = row.original

            return h('div', { class: 'flex items-center gap-2' }, [
                // h(Button, {
                //     variant: 'outline',
                //     class: 'flex items-center gap-2',
                //     onClick: () => onView(project_type),
                // }, {
                //     default: () => [
                //         h(Eye, { class: 'w-4 h-4' }),
                //         '',
                //     ],
                // }),
                h(Button, {
                    variant: 'newsecondary',
                    class: 'flex items-center gap-2',
                    onClick: () => onEdit(project_type),
                }, {
                    default: () => [
                        h(PenLine, { class: 'w-4 h-4' }),
                        '',
                    ],
                }),
                h(AlertDialog, {}, {
                    default: () => [
                        h(AlertDialogTrigger, { asChild: true }, {
                            default: () => h(Button, {
                                variant: 'newsecondary',
                                class: 'flex items-center gap-2 text-red-600',
                            }, { default: () => [h(Trash, { class: 'w-4 h-4' })] })
                        }),
                        h(AlertDialogContent, {}, {
                            default: () => [
                                h(AlertDialogHeader, {}, {
                                    default: () => [
                                        h(AlertDialogTitle, {}, 'Are you sure?'),
                                        h(AlertDialogDescription, {}, 'This action cannot be undone. It will permanently delete this project type.'),
                                    ]
                                }),
                                h(AlertDialogFooter, {}, {
                                    default: () => [
                                        h(AlertDialogCancel, {}, 'Cancel'),
                                        h(AlertDialogAction, {
                                            onClick: () => onDelete(project_type)
                                        }, { default: () => 'Delete' })
                                    ]
                                })
                            ]
                        })
                    ]
                })
            ])
        },
    },
]
