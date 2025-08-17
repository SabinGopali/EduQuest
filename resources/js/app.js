import './bootstrap';

function initializeKMeansScrollDemo() {
    const demoSectionElement = document.getElementById('kmeans-demo');
    if (!demoSectionElement) {
        return;
    }

    let hasDemoRun = false;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting && !hasDemoRun) {
                hasDemoRun = true;
                runKMeansDemo();
                observer.unobserve(demoSectionElement);
            }
        });
    }, { threshold: 0.3 });

    observer.observe(demoSectionElement);
}

function runKMeansDemo() {
    const stepsListElement = document.getElementById('kmeans-steps');
    const summaryElement = document.getElementById('kmeans-summary');

    if (!stepsListElement) {
        return;
    }

    const appendStepToList = (message) => {
        const listItemElement = document.createElement('li');
        listItemElement.textContent = message;
        stepsListElement.appendChild(listItemElement);
        stepsListElement.scrollTop = stepsListElement.scrollHeight;
    };

    const formatPoint = (p) => `(${p[0].toFixed(2)}, ${p[1].toFixed(2)})`;

    // Small synthetic dataset with 3 natural groupings
    const dataPoints = [
        [1.0, 1.1], [1.2, 0.9], [0.8, 1.3], [1.1, 0.7],
        [5.5, 6.1], [6.2, 5.9], [5.8, 6.5], [6.1, 5.6],
        [8.9, 1.0], [9.3, 1.2], [8.6, 0.7], [9.1, 0.9]
    ];

    const clusterCount = 3;
    const maximumIterations = 10;
    const centroidShiftTolerance = 0.0001;

    const computeEuclideanDistance = (pointA, pointB) => {
        const deltaX = pointA[0] - pointB[0];
        const deltaY = pointA[1] - pointB[1];
        return Math.sqrt(deltaX * deltaX + deltaY * deltaY);
    };

    const initializeCentroids = (points, k) => {
        const shuffled = points.slice().sort(() => Math.random() - 0.5);
        const selected = shuffled.slice(0, k);
        return selected.map((p) => [p[0], p[1]]);
    };

    const assignPointsToClusters = (points, centroids) => {
        const assignments = new Array(points.length).fill(0);
        let sumSquaredErrors = 0;
        for (let i = 0; i < points.length; i++) {
            let bestIndex = 0;
            let bestDistance = Number.POSITIVE_INFINITY;
            for (let c = 0; c < centroids.length; c++) {
                const distance = computeEuclideanDistance(points[i], centroids[c]);
                if (distance < bestDistance) {
                    bestDistance = distance;
                    bestIndex = c;
                }
            }
            assignments[i] = bestIndex;
            sumSquaredErrors += bestDistance * bestDistance;
        }
        return { assignments, sumSquaredErrors };
    };

    const recomputeCentroids = (points, assignments, k) => {
        const centroids = new Array(k).fill(null).map(() => [0, 0]);
        const counts = new Array(k).fill(0);
        for (let i = 0; i < points.length; i++) {
            const clusterIndex = assignments[i];
            centroids[clusterIndex][0] += points[i][0];
            centroids[clusterIndex][1] += points[i][1];
            counts[clusterIndex] += 1;
        }
        for (let c = 0; c < k; c++) {
            if (counts[c] > 0) {
                centroids[c][0] /= counts[c];
                centroids[c][1] /= counts[c];
            }
        }
        return centroids;
    };

    const computeCentroidShift = (oldCentroids, newCentroids) => {
        let totalShift = 0;
        for (let i = 0; i < oldCentroids.length; i++) {
            totalShift += computeEuclideanDistance(oldCentroids[i], newCentroids[i]);
        }
        return totalShift;
    };

    const arraysEqual = (a, b) => {
        if (!a || !b || a.length !== b.length) return false;
        for (let i = 0; i < a.length; i++) {
            if (a[i] !== b[i]) return false;
        }
        return true;
    };

    const renderSummary = (assignments, centroids) => {
        if (!summaryElement) return;
        const clusterSizes = new Array(clusterCount).fill(0);
        assignments.forEach((clusterIndex) => {
            clusterSizes[clusterIndex] += 1;
        });
        const centroidText = centroids
            .map((c, i) => `C${i + 1}: (${c[0].toFixed(2)}, ${c[1].toFixed(2)})`)
            .join('<br>');
        const sizesText = clusterSizes
            .map((s, i) => `Cluster ${i + 1}: ${s} points`)
            .join('<br>');
        summaryElement.innerHTML = `<strong>Final centroids</strong><br>${centroidText}<br><br><strong>Cluster sizes</strong><br>${sizesText}`;
    };

    let centroids = initializeCentroids(dataPoints, clusterCount);

    const stepIntervalMs = 650;
    let delayMs = 0;

    appendStepToList(`Initialization: choose k = ${clusterCount} random centroids → ${centroids.map(formatPoint).join(', ')}`);

    let lastAssignments = null;
    let converged = false;

    for (let iterationIndex = 1; iterationIndex <= maximumIterations; iterationIndex++) {
        ((iter) => {
            // Assignment step
            delayMs += stepIntervalMs;
            setTimeout(() => {
                appendStepToList(`Iteration ${iter}: assign each point to the nearest centroid`);
            }, delayMs);

            const { assignments, sumSquaredErrors } = assignPointsToClusters(dataPoints, centroids);

            delayMs += stepIntervalMs;
            setTimeout(() => {
                appendStepToList(`Iteration ${iter}: SSE = ${sumSquaredErrors.toFixed(3)}`);
            }, delayMs);

            // Update step
            const newCentroids = recomputeCentroids(dataPoints, assignments, clusterCount);
            const centroidShift = computeCentroidShift(centroids, newCentroids);

            delayMs += stepIntervalMs;
            setTimeout(() => {
                appendStepToList(`Iteration ${iter}: update centroids → ${newCentroids.map(formatPoint).join(', ')}`);
            }, delayMs);

            delayMs += stepIntervalMs;
            setTimeout(() => {
                appendStepToList(`Iteration ${iter}: centroid shift = ${centroidShift.toFixed(4)}`);
            }, delayMs);

            const assignmentsUnchanged = arraysEqual(assignments, lastAssignments);
            if (assignmentsUnchanged || centroidShift < centroidShiftTolerance || iter === maximumIterations) {
                converged = true;
                delayMs += stepIntervalMs;
                setTimeout(() => {
                    appendStepToList(assignmentsUnchanged
                        ? `Stop: assignments unchanged → converged`
                        : (centroidShift < centroidShiftTolerance
                            ? `Stop: centroid shift below tolerance → converged`
                            : `Stop: reached max iterations`));
                    renderSummary(assignments, newCentroids);
                }, delayMs);
            }

            // Prepare for next iteration
            centroids = newCentroids;
            lastAssignments = assignments;
        })(iterationIndex);

        if (converged) {
            break;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initializeKMeansScrollDemo();
});
