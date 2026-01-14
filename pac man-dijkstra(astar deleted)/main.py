# =========================
# 1. IMPORT THƯ VIỆN
# =========================
import pygame                  # Thư viện làm game
import sys                     # Thoát chương trình
from dijkstra import dijkstra_next_direction  # Ghost đuổi Pac-Man bằng Dijkstra
# =========================
# 2. CẤU HÌNH GAME
# =========================
TILE_SIZE = 32                 # Kích thước 1 ô
ROWS, COLS = 17, 18            # Kích thước map
WIDTH, HEIGHT = COLS * TILE_SIZE, ROWS * TILE_SIZE
FPS = 60                       # 60 frame / giây
GHOST_HOUSE_X, GHOST_HOUSE_Y = 7, 8  # Vị trí ghost hồi sinh


# =========================
# MÀU TÙY CHỈNH (RGB)
# =========================

BLACK = (0, 0, 0)
# Màu đen – nền màn hình, khoét miệng Pac-Man

BLUE = (33, 33, 222)
# Màu xanh dương – dùng cho tường (maze)

YELLOW = (255, 255, 0)
# Màu vàng – thân Pac-Man

WHITE = (255, 255, 255)
# Màu trắng – chấm thường, power pellet

RED = (222, 33, 33)
# Màu đỏ – trái cây

PINK = (255, 184, 255)
# Màu hồng – ghost (Pinky)

ORANGE = (255, 184, 82)
# Màu cam – ghost (Clyde)

CYAN = (0, 255, 255)
# Màu xanh cyan – ghost khi sợ (frightened)

GREEN = (0, 255, 0)
# Màu xanh lá – cuống trái cây


# =========================
# 4. MAP (MAZE)
# =========================
# 1 = tường
# 0 = chấm thường
# 2 = trống
# 3 = power pellet
# 4 = trái cây
MAZE = [
    [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1],
    [1,3,0,0,1,0,0,0,0,1,0,0,3,0,0,0,3,1],
    [1,0,1,0,1,0,1,1,0,1,0,1,0,1,0,1,0,1],
    [1,0,1,0,0,4,0,0,0,0,0,1,0,0,0,1,0,1],
    [1,0,1,0,1,1,0,0,1,1,0,1,0,1,0,1,0,1],
    [1,0,0,0,1,0,0,0,0,1,0,0,0,1,0,0,0,1],
    [1,1,1,0,1,0,1,1,0,1,0,1,1,1,0,1,1,1],
    [2,2,1,0,0,0,0,0,0,0,0,1,0,4,0,1,2,2],
    [1,1,1,0,1,1,1,1,1,1,0,1,1,1,0,1,1,1],
    [1,0,4,0,1,0,0,0,0,1,0,0,0,1,0,0,0,1],
    [1,0,1,1,1,0,1,1,0,1,1,1,0,1,1,1,0,1],
    [1,3,0,0,0,0,1,1,0,0,0,0,3,0,0,0,3,1],
    [1,0,1,0,1,0,1,1,0,1,0,1,0,1,0,1,0,1],
    [1,0,1,0,0,0,0,0,4,0,0,1,0,0,0,1,0,1],
    [1,0,1,0,1,1,0,0,1,1,0,1,0,1,0,1,0,1],
    [1,0,0,0,1,0,0,0,0,1,0,0,0,1,0,0,0,1],
    [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1]
]

class Entity:
    # =========================
    # CLASS ENTITY
    # → Dùng chung cho Pac-Man và Ghost
    # =========================

    def __init__(self, x, y, color, is_pacman=False, move_delay=1):

        # -------------------------
        # VỊ TRÍ TRÊN MAP (theo ô)
        # -------------------------
        self.x = x
        self.y = y

        # Vị trí ban đầu (dùng khi respawn / mất mạng)
        self.start_x = x
        self.start_y = y

        # -------------------------
        # HIỂN THỊ
        # -------------------------
        self.color = color
        # Màu của entity (Pac-Man hoặc Ghost)

        # -------------------------
        # DI CHUYỂN
        # -------------------------
        self.dir = (0, 0)
        # Hướng đang di chuyển: (dx, dy)

        self.next_dir = (0, 0)
        # Hướng kế tiếp (để xử lý rẽ mượt)

        self.is_pacman = is_pacman
        # Phân biệt Pac-Man hay Ghost

        # -------------------------
        # TỐC ĐỘ / NHỊP DI CHUYỂN
        # -------------------------
        self.move_delay = move_delay
        # Số frame cần chờ trước khi di chuyển 1 ô

        self.move_counter = 0
        # Bộ đếm frame để áp dụng move_delay

        # -------------------------
        # TRẠNG THÁI GHOST
        # -------------------------
        self.frightened = False
        # Ghost có đang sợ hay không (ăn power pellet)

        self.frightened_timer = 0
        # Thời gian ghost còn sợ (đếm ngược)

        self.respawn_timer = 0
        # Thời gian ghost biến mất sau khi bị ăn

        # -------------------------
        # RIÊNG CHO PAC-MAN
        # -------------------------
        if is_pacman:
            self.mouth_angle = 0.25
            # Độ mở miệng Pac-Man (radian)

            self.mouth_direction = 1
            # Hướng animation miệng
            #  1  = mở ra
            # -1 = khép lại


    def move(self, maze):
        # =========================
        # HÀM DI CHUYỂN ENTITY
        # =========================

        # Tính vị trí kế tiếp theo hướng hiện tại
        nx, ny = self.x + self.dir[0], self.y + self.dir[1]

        # Kiểm tra:
        # 1. Không vượt biên map
        # 2. Ô tiếp theo KHÔNG phải là tường (maze != 1)
        if 0 <= nx < COLS and 0 <= ny < ROWS and maze[ny][nx] != 1:
            # Cập nhật vị trí nếu hợp lệ
            self.x, self.y = nx, ny


    def animate_mouth(self):
        # =========================
        # ANIMATION MIỆNG PAC-MAN
        # =========================

        # Chỉ Pac-Man mới có animation miệng
        if self.is_pacman:

            # Tăng / giảm độ mở miệng theo hướng hiện tại
            self.mouth_angle += 0.02 * self.mouth_direction

            # Nếu mở quá lớn → đảo chiều (bắt đầu khép)
            if self.mouth_angle > 0.25:
                self.mouth_angle = 0.25
                self.mouth_direction = -1

            # Nếu khép quá nhỏ → đảo chiều (bắt đầu mở)
            elif self.mouth_angle < 0.05:
                self.mouth_angle = 0.05
                self.mouth_direction = 1


    def draw(self, screen):
        # =========================
        # HÀM VẼ ENTITY LÊN MÀN HÌNH
        # =========================

        # Chuyển từ tọa độ ô (x, y) → tọa độ pixel (tâm hình)
        cx = self.x * TILE_SIZE + TILE_SIZE // 2
        cy = self.y * TILE_SIZE + TILE_SIZE // 2

        # Bán kính nhân vật
        r = TILE_SIZE // 2 - 2
        if self.is_pacman:
            # =========================
            # VẼ PAC-MAN
            # =========================

            # Ánh xạ hướng di chuyển → góc quay (độ)
            angle_map = {
                (1, 0): 0,      # sang phải
                (0, -1): 90,    # lên trên
                (-1, 0): 180,   # sang trái
                (0, 1): 270     # xuống dưới
            }

            # Lấy góc tương ứng với hướng hiện tại
            angle = angle_map.get(self.dir, 0)
            # Tính góc bắt đầu / kết thúc của miệng
            start_angle = (angle - self.mouth_angle * 180 / 3.14) % 360
            end_angle   = (angle + self.mouth_angle * 180 / 3.14) % 360
            # Vẽ thân Pac-Man (hình tròn vàng)
            pygame.draw.circle(screen, YELLOW, (cx, cy), r)
            # Tạo khung hình tròn để vẽ cung tròn (arc)
            mouth_rect = pygame.Rect(cx - r, cy - r, r * 2, r * 2)

            # Vẽ cung tròn màu đen để "khoét" miệng
            pygame.draw.arc(
                screen, BLACK, mouth_rect,
                (angle - self.mouth_angle) * 3.14 / 180,
                (angle + self.mouth_angle) * 3.14 / 180,
                r
            )
            # =========================
            # VẼ MIỆNG DẠNG TAM GIÁC
            # =========================

            mouth_length = r
            mouth_angle_rad = self.mouth_angle * 3.14
            # Điểm trung tâm (đỉnh tam giác)
            x1 = cx
            y1 = cy
            # Cạnh trên của miệng (xoay theo hướng)
            x2 = cx + mouth_length * pygame.math.Vector2(1, 0).rotate(
                -angle + self.mouth_angle * 180 / 3.14
            ).x
            y2 = cy + mouth_length * pygame.math.Vector2(1, 0).rotate(
                -angle + self.mouth_angle * 180 / 3.14
            ).y
            # Cạnh dưới của miệng
            x3 = cx + mouth_length * pygame.math.Vector2(1, 0).rotate(
                -angle - self.mouth_angle * 180 / 3.14
            ).x
            y3 = cy + mouth_length * pygame.math.Vector2(1, 0).rotate(
                -angle - self.mouth_angle * 180 / 3.14
            ).y
            # Vẽ tam giác màu đen → phần miệng bị cắt
            pygame.draw.polygon(
                screen, BLACK,
                [(x1, y1), (x2, y2), (x3, y3)]
            )
        else:
            # =========================
            # VẼ GHOST
            # =========================

            # Nếu ghost đang respawn → không vẽ
            if self.respawn_timer > 0:
                return
            # Ghost sợ → màu CYAN, bình thường → màu riêng
            color = CYAN if self.frightened else self.color

            # Vẽ ghost bằng hình tròn
            pygame.draw.circle(screen, color, (cx, cy), r)


    def move_with_delay(self, maze):
        # =========================
        # DI CHUYỂN CÓ ĐỘ TRỄ (SPEED CONTROL)
        # =========================

        # Mỗi frame, tăng bộ đếm
        self.move_counter += 1
        # Khi số frame đạt tới move_delay
        if self.move_counter >= self.move_delay:
            # Thực hiện di chuyển thật sự
            self.move(maze)

            # Reset bộ đếm để chờ lượt tiếp theo
            self.move_counter = 0
def draw_maze(screen, maze):
    # =========================
    # VẼ BẢN ĐỒ (MAZE)
    # =========================
    # Duyệt từng hàng (y) và từng cột (x)
    for y, row in enumerate(maze):
        for x, tile in enumerate(row):
            if tile == 1:
                # =========================
                # WALL – TƯỜNG
                # =========================
                pygame.draw.rect( #ve hcn
                    screen, BLUE,
                    (x*TILE_SIZE, y*TILE_SIZE, TILE_SIZE, TILE_SIZE),
                    border_radius=8 #ban kinh vien
                )
            elif tile == 0:
                # =========================
                # DOT – CHẤM THƯỜNG
                # =========================
                pygame.draw.circle( #ve hinh tron
                    screen, WHITE,
                    (x*TILE_SIZE + TILE_SIZE//2,
                     y*TILE_SIZE + TILE_SIZE//2),
                    4
                )
            elif tile == 3:
                # =========================
                # POWER PELLET – CHẤM LỚN
                # =========================
                pygame.draw.circle(
                    screen, WHITE,
                    (x*TILE_SIZE + TILE_SIZE//2,
                     y*TILE_SIZE + TILE_SIZE//2),
                    8
                )
            elif tile == 4:
                # =========================
                # FRUIT – TRÁI CÂY
                # =========================

                # Thân trái cây
                pygame.draw.circle(
                    screen, RED,
                    (x*TILE_SIZE + TILE_SIZE//2,
                     y*TILE_SIZE + TILE_SIZE//2),
                    7
                )

                # Cuống trái cây
                pygame.draw.circle(
                    screen, GREEN,
                    (x*TILE_SIZE + TILE_SIZE//2,
                     y*TILE_SIZE + TILE_SIZE//2 - 6),
                    3
                )


def is_walkable(x, y, maze):
    # =========================
    # KIỂM TRA Ô CÓ ĐI ĐƯỢC HAY KHÔNG
    # =========================

    return (
        0 <= x < COLS        # Không vượt biên trái / phải của map
        and
        0 <= y < ROWS        # Không vượt biên trên / dưới của map
        and
        maze[y][x] != 1      # Ô đó KHÔNG phải là tường
    )


def main():
    # =========================
    # KHỞI TẠO PYGAME
    # =========================

    pygame.init()
    # Khởi động toàn bộ module pygame

    screen = pygame.display.set_mode((WIDTH, HEIGHT))
    # Tạo cửa sổ game với kích thước map

    pygame.display.set_caption("Pac-Man")
    # Tiêu đề cửa sổ

    clock = pygame.time.Clock()
    # Dùng để giới hạn FPS
    # =========================
    # TẠO NHÂN VẬT
    # =========================

    pacman = Entity(
        7, 10,               # Vị trí bắt đầu trên map
        YELLOW,              # Màu Pac-Man
        is_pacman=True,      # Đánh dấu là Pac-Man
        move_delay=6         # Tốc độ Pac-Man
    )
    ghosts = [
        # =========================
        # DANH SÁCH GHOST
        # =========================

        Entity(6, 5, RED,    move_delay=8),     # Ghost đỏ
        Entity(7, 5, PINK,   move_delay=8),     # Ghost hồng
        Entity(8, 5, CYAN,   move_delay=8),     # Ghost xanh
        Entity(7, 6, ORANGE, move_delay=8)      # Ghost cam
    ]
    # =========================
    # TRẠNG THÁI GAME
    # =========================

    score = 0
    # Điểm ban đầu

    algorithm = "dijkstra"
    # Thuật toán điều khiển ghost (cố định)

    lives = 3
    # Số mạng của Pac-Man
    # =========================
    # CỜ ĐIỀU KHIỂN VÒNG LẶP
    # =========================

    running = True
    # Game còn chạy hay không

    won = False

    paused = False
    # Trạng thái tạm dừng
    # =========================
    # CHẾ ĐỘ HỖ TRỢ / DEBUG
    # =========================

    debug_mode = False
    # Bật/tắt vẽ đường đi Dijkstra

    auto_pilot = False
    # Pac-Man tự động ăn pellet

    auto_pilot_path = []
    # Danh sách ô Pac-Man sẽ đi theo

    auto_pilot_target = None
    # Mục tiêu hiện tại (tọa độ pellet)

    while running:
        # =========================
        # VÒNG LẶP CHÍNH CỦA GAME
        # =========================

        lost_life = False
        # Đánh dấu Pac-Man vừa mất mạng trong frame này hay không
        # =========================
        # VẼ NỀN & MAP
        # =========================

        screen.fill(BLACK)
        # Xóa khung hình cũ

        draw_maze(screen, MAZE)
        # Vẽ bản đồ (tường, chấm, trái cây)
        # =========================
        # XỬ LÝ SỰ KIỆN (INPUT)
        # =========================

        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                # Người chơi đóng cửa sổ
                running = False
            elif event.type == pygame.KEYDOWN:
                # =========================
                # BẮT PHÍM BẤM
                # =========================
                if event.key == pygame.K_p:
                    # Phím P → tạm dừng / tiếp tục
                    paused = not paused #not la toan tu dao nguoc
                if paused:
                    # Nếu đang pause → bỏ qua input còn lại
                    continue
                if event.key == pygame.K_a:
                    # Phím A → bật/tắt Auto-Pilot
                    auto_pilot = not auto_pilot

                    # Reset đường đi tự động
                    auto_pilot_path = []
                    auto_pilot_target = None
                if event.key == pygame.K_LEFT:
                    # Pac-Man muốn rẽ trái
                    pacman.next_dir = (-1, 0)

                    # Tắt Auto-Pilot khi người chơi điều khiển
                    auto_pilot = False
                elif event.key == pygame.K_RIGHT:
                    # Rẽ phải
                    pacman.next_dir = (1, 0)
                    auto_pilot = False
                elif event.key == pygame.K_UP:
                    # Đi lên
                    pacman.next_dir = (0, -1)
                    auto_pilot = False
                elif event.key == pygame.K_DOWN:
                    # Đi xuống
                    pacman.next_dir = (0, 1)
                    auto_pilot = False
                elif event.key == pygame.K_d:
                    # Bật / tắt chế độ debug (vẽ đường Dijkstra)
                    debug_mode = not debug_mode


        # =========================
        # TRẠNG THÁI PAUSE GAME
        # =========================
        if paused:
            # Hiển thị chữ "PAUSED" khi game tạm dừng
            font = pygame.font.SysFont("", 48)
            pause_text = font.render("PAUSED", True, WHITE)

            # Vẽ chữ PAUSED ra giữa màn hình
            screen.blit(pause_text, (WIDTH // 2 - 100, HEIGHT // 2 - 24))

            # Cập nhật màn hình
            pygame.display.flip()

            # Giảm FPS khi pause để tiết kiệm tài nguyên
            clock.tick(10)

            # Bỏ qua toàn bộ logic game bên dưới
            continue

        # =========================
        # AUTO-PILOT LOGIC
        # TÌM CHẤM (PELLET) GẦN NHẤT
        # =========================
        def find_nearest_pellet(start_x, start_y, maze):
            # Sử dụng BFS (Breadth-First Search)
            from collections import deque

            # Mảng đánh dấu ô đã đi qua
            visited = [[False for _ in range(COLS)] for _ in range(ROWS)]

            # Hàng đợi BFS
            queue = deque()

            # Thêm vị trí bắt đầu (Pac-Man)
            queue.append((start_x, start_y, 0))
            visited[start_y][start_x] = True

            # Duyệt BFS
            while queue:
                x, y, dist = queue.popleft()

                # Nếu gặp chấm thường hoặc chấm lớn
                if maze[y][x] == 0 or maze[y][x] == 3:
                    return (x, y)

                # Duyệt 4 hướng
                for dx, dy in [(1,0), (-1,0), (0,1), (0,-1)]:
                    nx, ny = x + dx, y + dy

                    # Kiểm tra hợp lệ và không phải tường
                    if (0 <= nx < COLS and
                        0 <= ny < ROWS and
                        not visited[ny][nx] and
                        maze[ny][nx] != 1):

                        visited[ny][nx] = True
                        queue.append((nx, ny, dist + 1))

            # Không còn chấm nào
            return None


        # =========================
        # DIJKSTRA PATHFINDING
        # TÌM ĐƯỜNG ĐI NGẮN NHẤT
        # =========================
        def get_path(start, goal, maze):
            # Thuật toán Dijkstra (chỉ dùng cho Auto-Pilot)
            # Trả về danh sách các ô (x, y) từ start → goal
            import heapq

            directions = [(1,0), (-1,0), (0,1), (0,-1)]
            ROWS, COLS = len(maze), len(maze[0])

            # Mảng khoảng cách
            dist = [[float('inf') for _ in range(COLS)] for _ in range(ROWS)]
            # Mảng lưu ô trước đó để truy vết đường đi
            prev = [[None for _ in range(COLS)] for _ in range(ROWS)]

            # Priority Queue cho Dijkstra
            heap = []
            heapq.heappush(heap, (0, start[0], start[1]))
            dist[start[1]][start[0]] = 0

            # Vòng lặp chính của Dijkstra
            while heap:
                cost, x, y = heapq.heappop(heap)

                # Nếu đã tới mục tiêu thì dừng
                if (x, y) == goal:
                    break

                # Duyệt 4 hướng
                for dx, dy in directions:
                    nx, ny = x + dx, y + dy

                    # Kiểm tra hợp lệ và không phải tường
                    if 0 <= nx < COLS and 0 <= ny < ROWS and maze[ny][nx] != 1:
                        new_cost = cost + 1

                        # Cập nhật đường đi ngắn hơn
                        if new_cost < dist[ny][nx]:
                            dist[ny][nx] = new_cost
                            prev[ny][nx] = (x, y)
                            heapq.heappush(heap, (new_cost, nx, ny))

            # =========================
            # TRUY VẾT ĐƯỜNG ĐI
            # =========================
            path = []
            x, y = goal

            while prev[y][x] is not None and (x, y) != start:
                path.append((x, y))
                x, y = prev[y][x]

            if (x, y) == start:
                path.append((x, y))
                path.reverse()
                return path

            return []

        # =========================
        # DI CHUYỂN PAC-MAN
        # =========================
        if auto_pilot:
            # Nếu chưa có đường đi hoặc mục tiêu không còn hợp lệ
            if (not auto_pilot_path or
                auto_pilot_target is None or
                MAZE[auto_pilot_target[1]][auto_pilot_target[0]] not in [0, 3]):

                # Tìm chấm gần nhất
                target = find_nearest_pellet(pacman.x, pacman.y, MAZE)

                if target:
                    auto_pilot_target = target
                    path = get_path((pacman.x, pacman.y), auto_pilot_target, MAZE)

                    # Bỏ qua vị trí hiện tại
                    auto_pilot_path = path[1:] if len(path) > 1 else []
                else:
                    auto_pilot_path = []
                    auto_pilot_target = None

            # =========================
            # DI CHUYỂN THEO ĐƯỜNG ĐI
            # =========================
            if auto_pilot_path:
                next_pos = auto_pilot_path[0]

                dx = next_pos[0] - pacman.x
                dy = next_pos[1] - pacman.y
                pacman.next_dir = (dx, dy)

                nx, ny = pacman.x + dx, pacman.y + dy
                if is_walkable(nx, ny, MAZE):
                    pacman.dir = pacman.next_dir

                pacman.move_with_delay(MAZE)
                pacman.animate_mouth()

                # Nếu đã tới ô tiếp theo thì bỏ khỏi path
                if (pacman.x, pacman.y) == next_pos:
                    auto_pilot_path.pop(0)
            else:
                pacman.animate_mouth()

        else:
            # =========================
            # ĐIỀU KHIỂN THỦ CÔNG
            # =========================
            nx = pacman.x + pacman.next_dir[0]
            ny = pacman.y + pacman.next_dir[1]

            if is_walkable(nx, ny, MAZE):
                pacman.dir = pacman.next_dir

            pacman.move_with_delay(MAZE)
            pacman.animate_mouth()

        # =========================
        # ĂN CHẤM & TÍNH ĐIỂM
        # =========================
        # Kiểm tra thắng: hết chấm thường + power pellet
        if all(0 not in row and 3 not in row for row in MAZE):
            won = True
            running = False


        if MAZE[pacman.y][pacman.x] == 0:
            # Ăn chấm thường
            MAZE[pacman.y][pacman.x] = 2
            score += 10

        elif MAZE[pacman.y][pacman.x] == 3:
            # Ăn chấm lớn → Ghost sợ
            MAZE[pacman.y][pacman.x] = 2
            score += 50

            for ghost in ghosts:
                ghost.frightened = True
                ghost.frightened_timer = FPS * 7  # 7 giây

        elif MAZE[pacman.y][pacman.x] == 4:
            # Ăn trái cây
            MAZE[pacman.y][pacman.x] = 2
            score += 100


                # =========================
        # DI CHUYỂN GHOST (DIJKSTRA)
        # =========================
        for ghost in ghosts:

            # =========================
            # GHOST ĐANG RESPAWN
            # =========================
            if ghost.respawn_timer > 0:
                ghost.respawn_timer -= 1
                continue
                # Bỏ qua di chuyển và va chạm khi ghost đang hồi sinh

            # =========================
            # TRẠNG THÁI SỢ HÃI
            # =========================
            if ghost.frightened:
                ghost.frightened_timer -= 1
                if ghost.frightened_timer <= 0:
                    ghost.frightened = False
                    # Hết thời gian sợ → quay lại bình thường
            else:
                # Ghost đuổi Pac-Man bằng Dijkstra
                ghost.dir = dijkstra_next_direction(ghost, pacman, MAZE)

            # =========================
            # DI CHUYỂN GHOST
            # =========================
            ghost.move_with_delay(MAZE)

            # =========================
            # KIỂM TRA VA CHẠM
            # =========================
            if ghost.x == pacman.x and ghost.y == pacman.y:

                if ghost.frightened:
                    # Pac-Man ăn ghost
                    score += 200
                    ghost.x, ghost.y = GHOST_HOUSE_X, GHOST_HOUSE_Y
                    ghost.frightened = False
                    ghost.frightened_timer = 0
                    ghost.respawn_timer = FPS * 2
                    continue

                else:
                    # Ghost ăn Pac-Man
                    lives -= 1

                    if lives == 0:
                        # Hết mạng → kết thúc game
                        running = False
                        break
                    else:
                        # Reset vị trí sau khi mất mạng
                        pacman.x, pacman.y = 7, 10
                        pacman.dir = (0, 0)

                        for g in ghosts:
                            g.x, g.y = g.start_x, g.start_y
                            g.dir = (0, 0)

                        lost_life = True
                        break

            if lost_life:
                break

        # =========================
        # VẼ NHÂN VẬT
        # =========================
        pacman.draw(screen)
        for ghost in ghosts:
            ghost.draw(screen)

        # =========================
        # DEBUG MODE: VẼ ĐƯỜNG ĐI GHOST
        # =========================
        if debug_mode:
            path_colors = [RED, PINK, CYAN, ORANGE]

            for idx, ghost in enumerate(ghosts):
                path = get_full_path(ghost, pacman, MAZE)
                if path:
                    draw_path(
                        screen,
                        path,
                        path_colors[idx % len(path_colors)]
                    )

        # =========================
        # HIỂN THỊ ĐIỂM SỐ
        # =========================
        font = pygame.font.SysFont("Arial", 24)
        score_text = font.render(f"Score: {score}", True, WHITE)
        screen.blit(score_text, (10, HEIGHT - 30))

        # =========================
        # HIỂN THỊ THUẬT TOÁN
        # =========================
        algo_text = font.render("Algorithm: Dijkstra", True, WHITE)
        screen.blit(algo_text, (200, HEIGHT - 30))

        # =========================
        # HIỂN THỊ AUTO-PILOT
        # =========================
        if auto_pilot:
            auto_text = font.render("Auto-Pilot ON (A)", True, CYAN)
            screen.blit(auto_text, (400, HEIGHT - 30))

        # =========================
        # HIỂN THỊ SỐ MẠNG CÒN LẠI
        # =========================
        for i in range(lives):
            pygame.draw.circle(
                screen,
                YELLOW,
                (120 + i * 30, HEIGHT - 15),
                10
            )

        # =========================
        # CẬP NHẬT FRAME
        # =========================
        pygame.display.flip()
        clock.tick(FPS)

        # =========================
        # TẠM DỪNG NGẮN SAU KHI MẤT MẠNG
        # =========================
        if lost_life:
            pygame.time.wait(500)
            continue


    # =========================
    # KIỂM TRA ĐIỀU KIỆN CHIẾN THẮNG
    # =========================
    # Người chơi thắng khi không còn chấm thường (0)
    # và không còn chấm sức mạnh (3) trên bản đồ
    won = True
    for row in MAZE:
        if 0 in row or 3 in row:
            won = False
            break

    # =========================
    # HIỂN THỊ KẾT QUẢ CUỐI GAME
    # =========================
    if won:
        # Hiển thị thông báo thắng
        font = pygame.font.SysFont("Arial", 48)
        win_text = font.render("YOU WIN!", True, GREEN)
        screen.blit(win_text, (WIDTH // 2 - 120, HEIGHT // 2 - 24))
        pygame.display.flip()
        pygame.time.wait(2000)

    elif lives == 0:
        # Hiển thị thông báo thua
        font = pygame.font.SysFont("Arial", 48)
        game_over_text = font.render("GAME OVER", True, RED)
        screen.blit(game_over_text, (WIDTH // 2 - 120, HEIGHT // 2 - 24))
        pygame.display.flip()
        pygame.time.wait(2000)

    # =========================
    # THOÁT GAME
    # =========================
    pygame.quit()
    sys.exit()

# =========================
# DEBUG: HỖ TRỢ VẼ ĐƯỜNG ĐI
# =========================

def get_full_path(ghost, pacman, maze):
    # Trả về toàn bộ đường đi từ Ghost → Pac-Man
    # Dùng Dijkstra, phục vụ chế độ Debug
    import heapq

    directions = [(1,0), (-1,0), (0,1), (0,-1)]
    ROWS, COLS = len(maze), len(maze[0])

    # Mảng khoảng cách và truy vết
    dist = [[float('inf') for _ in range(COLS)] for _ in range(ROWS)]
    prev = [[None for _ in range(COLS)] for _ in range(ROWS)]

    # Priority Queue
    heap = []
    heapq.heappush(heap, (0, ghost.x, ghost.y))
    dist[ghost.y][ghost.x] = 0

    # Dijkstra
    while heap:
        cost, x, y = heapq.heappop(heap)

        # Đã tới Pac-Man
        if (x, y) == (pacman.x, pacman.y):
            break

        for d in directions:
            nx, ny = x + d[0], y + d[1]

            if 0 <= nx < COLS and 0 <= ny < ROWS and maze[ny][nx] != 1:
                new_cost = cost + 1
                if new_cost < dist[ny][nx]:
                    dist[ny][nx] = new_cost
                    prev[ny][nx] = (x, y)
                    heapq.heappush(heap, (new_cost, nx, ny))

    # =========================
    # TRUY VẾT ĐƯỜNG ĐI
    # =========================
    path = []
    x, y = pacman.x, pacman.y

    while prev[y][x] is not None and (x, y) != (ghost.x, ghost.y):
        path.append((x, y))
        x, y = prev[y][x]

    if (x, y) == (ghost.x, ghost.y):
        path.append((x, y))
        path.reverse()
        return path

    return None

def draw_path(screen, path, color):
    # Vẽ đường đi (path) lên màn hình bằng đường thẳng
    # Mỗi path là danh sách các ô (x, y)
    if len(path) < 2:
        return

    points = [
        (x * TILE_SIZE + TILE_SIZE // 2,
         y * TILE_SIZE + TILE_SIZE // 2)
        for (x, y) in path
    ]

    pygame.draw.lines(screen, color, False, points, 4)

# =========================
# CHẠY CHƯƠNG TRÌNH
# =========================
if __name__ == "__main__":
    main()
