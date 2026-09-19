import { flushPromises, mount } from '@vue/test-utils'
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import Tool from '../../resources/js/components/Tool.vue'

const relationshipQuery = 'orderBy=created_at&orderByDirection=desc&viaResource=posts&viaResourceId=42&viaRelationship=comments&relationshipType=hasMany'
const firstPageUrl = `/nova-api/comments?page=1&${relationshipQuery}`
const secondPageUrl = `/nova-api/comments?page=2&${relationshipQuery}`

describe('Tool', () => {
    const request = { get: vi.fn(), post: vi.fn() }

    beforeEach(() => {
        request.get.mockReset()
        globalThis.Nova = {
            request: () => request,
            url: vi.fn((path) => path),
            error: vi.fn(),
            success: vi.fn(),
        }
    })

    afterEach(() => {
        delete globalThis.Nova
    })

    it('uses Nova 5 camel-case pagination URLs and preserves relationship context while navigating', async () => {
        request.get
            .mockResolvedValueOnce({ data: { resources: [], nextPageUrl: secondPageUrl, prevPageUrl: '' } })
            .mockResolvedValueOnce({ data: { resources: [], nextPageUrl: '', prevPageUrl: firstPageUrl } })
            .mockResolvedValueOnce({ data: { resources: [], nextPageUrl: secondPageUrl, prevPageUrl: '' } })

        const wrapper = mount(Tool, {
            props: { resourceName: 'posts', resourceId: '42', field: {} },
            global: { stubs: { Comment: true } },
        })

        await flushPromises()

        const [older, newer] = wrapper.get('nav').findAll('button')
        expect(older.text()).toBe('Older')
        expect(newer.attributes('disabled')).toBeDefined()
        expect(request.get).toHaveBeenLastCalledWith(firstPageUrl)

        await older.trigger('click')
        await flushPromises()

        expect(request.get).toHaveBeenLastCalledWith(secondPageUrl)
        expect(newer.attributes('disabled')).toBeUndefined()

        await newer.trigger('click')
        await flushPromises()

        expect(request.get).toHaveBeenLastCalledWith(firstPageUrl)
    })
})
